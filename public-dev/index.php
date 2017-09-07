<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$config = [
    'displayErrorDetails'    => false,
    'addContentLengthHeader' => false,
    'accountkit'             => [
        'secret' => '',
        'token'  => '',
        'apiver' => '',
        'appid'  => '',
    ],
    'recaptcha'              => [
        'sitekey' => '',
        'secret'  => '',
    ],
    'mailer'                 => [
        'from'    => '',
        'to'      => '',
        'host'    => '',
    ],
];

$app = new \Slim\App(['settings' => $config]);

$app->add(function(Request $request, Response $response, $next) {
    $cf = new \CloudFlare\IpRewrite();
    if ($cf->isCloudFlare()) {
        /**
         * @var \Slim\Http\Environment $env
         */
        $env = $this->environment;
        $env['cf.original_ip'] = $cf->getOriginalIP();
        $env['REMOTE_ADDR']    = $cf->getRewrittenIP();
    }

    return $next($request, $response);
});

$container = $app->getContainer();
$container['view']       = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');
$container['accountkit'] = function ($container) {
    $settings   = $container['settings']['accountkit'];
    $accountkit = new \WildWolf\AccountKit($settings['appid'], $settings['secret']);
    $accountkit->setApiVersion($settings['apiver']);
    return $accountkit;
};

$container['recaptcha'] = function ($container) {
    $settings  = $container['settings']['recaptcha'];
    $recaptcha = new ReCaptcha\ReCaptcha($settings['secret']);
    return $recaptcha;
};

$container['notFoundHandler'] = function ($container) {
    return function (Request $request, Response $response) use ($container) {
        return $container['view']->render($response, '404.phtml')->withStatus(404);
    };
};

$container['notAllowedHandler'] = function ($container) {
    return function (Request $request, Response $response) use ($container) {
        return $response->withHeader('Location', '/')->withStatus(302);
    };
};

$container['phpErrorHandler'] = function ($container) {
    return function (Request $request, Response $response) use ($container) {
        return $container['view']->render($response, '500.phtml')->withStatus(500);
    };
};

$app->get('/', function (Request $request, Response $response) {
    session_start();

    if (isset($_SESSION['email'])) {
        return $response->withHeader('Location', '/start')->withStatus(302);
    }

    $accountkit = $this->get('settings')['accountkit'];
    $header_js  = ['https://sdk.accountkit.com/ru_RU/sdk.js'];
    $footer_js  = ['/js/login.js'];
    return $this->view->render($response, 'index.phtml', ['accountkit' => $accountkit, 'header_js' => $header_js, 'footer_js' => $footer_js]);
});

$app->post('/login', function (Request $request, Response $response) {
    $data   = $request->getParsedBody();

    $status = $data['status'] ?? null;
    $code   = $data['code']   ?? null;

    /**
     * @var \WildWolf\AccountKit $kit
     */
    try {
        $kit    = $this->accountkit;
        $d1     = $kit->getAccessToken($code);
        $d2     = $kit->validateAccessToken($d1->access_token);

        session_start();
        $_SESSION['email'] = $d2->email->address;
        return $response->withHeader('Location', '/start')->withStatus(302);
    }
    catch (\Exception $e) {
        return $this->view->render($response, 'loginfailed.phtml');
    }
});

$app->post('/heartbeat', function (Request $request, Response $response) {
    session_start();
    return $response->withStatus(204);
});

$app->get('/start', function (Request $request, Response $response) {
    session_start();
    if (!isset($_SESSION['email'])) {
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    $recaptcha = $this->get('settings')['recaptcha'];

    $data = (!empty($_SESSION['data']))
        ? $_SESSION['data']
        : ['name' => '', 'country' => '', 'address' => '', 'phone' => '', 'desc' => '', 'url' => '', 'present' => '', 'comment' => '']
    ;

    $errs = empty($_SESSION['errors']) ? [] : $_SESSION['errors'];
    unset($_SESSION['errors'], $_SESSION['data']);

    $footer_js = ['https://www.google.com/recaptcha/api.js', '/js/form.js'];
    return $this->view->render($response, 'form.phtml', ['footer_js' => $footer_js, 'recaptcha' => $recaptcha, 'data' => $data, 'errors' => $errs]);
});

$app->post('/submit', function (Request $request, Response $response) {
    session_start();
    if (!isset($_SESSION['email'])) {
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    $post = $request->getParsedBody();
    $form = $post['data'] ?? [];
    if (!is_array($form)) {
        $form = [];
    }

    $data['name']     = $form['name']    ?? '';
    $data['country']  = $form['country'] ?? '';
    $data['address']  = $form['address'] ?? '';
    $data['phone']    = $form['phone']   ?? '';
    $data['desc']     = $form['desc']    ?? '';
    $data['url']      = $form['url']     ?? '';
    $data['comment']  = $form['comment'] ?? '';
    $data             = array_map('trim', $data);
    $data['present']  = $form['present'] ?? '';

    if ($data['present'] !== '') {
        $data['present'] = (int)$data['present'];
    }

    $_SESSION['data'] = $data;
    $errors           = [];

    $recaptcha = $this->recaptcha;
    $grr       = $post['g-recaptcha-response'] ?? null;
    $ip        = $this->environment['REMOTE_ADDR'] ?? null;
    $result    = $recaptcha->verify($grr, $ip);

    if (!$result->isSuccess()) {
        $errors[] = 'ReCaptcha не пройдена. Попробуте ещё раз.';
    }

    if (empty($data['name'])) {
        $errors[] = 'Пожалуйста, заполните поле «ФИО»';
    }

    if (empty($data['country'])) {
        $errors[] = 'Пожалуйста, заполните поле «Страна»';
    }

    if (empty($data['desc'])) {
        $errors[] = 'Пожалуйста, заполните поле «Описание»';
    }

    if ($data['present'] === '') {
        $errors[] = 'Вы проверили наличие объекта в Чистилище?';
    }

    if ($data['present'] === 1 && empty($data['url'])) {
        $errors[] = 'Пожалуйста, укажите адрес существующей записи в Чистилище.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        return $response->withHeader('Location', '/start')->withStatus(302);
    }

    $message  = '';
    if ($data['present']) {
        $message .= "Обновление информации: {$data['url']}\n";
    }

    $message .= "ФИО: {$data['name']}\n";
    $message .= "Страна: {$data['country']}\n";

    if ($data['address']) $message .= "Адрес: {$data['address']}\n";
    if ($data['phone'])   $message .= "Телефон: {$data['phone']}\n";

    $message .= "Описание:\n{$data['desc']}\n\n";

    if ($data['comment'])   $message .= "Примечание: {$data['comment']}\n";

    $message .= "-----\nОтправитель: {$_SESSION['email']}\n";
    $message .= "IP: {$ip}\n";

    $settings = $this->settings['mailer'];
    $mailer = new PHPMailer();
    $mailer->From = $settings['from'];
    $mailer->FromName = '';
    $mailer->addReplyTo($_SESSION['email']);
    $mailer->Subject = 'Запрос на внесение в Чистилище';
    $mailer->addAddress($settings['to']);
    $mailer->Body = $message;
    $mailer->CharSet = 'utf-8';

    $files = $request->getUploadedFiles();
    foreach ($files['files'] as $f) {
        if (!$f->getError()) {
            $mailer->addAttachment($f->file, basename($f->getClientFilename()), 'base64', $f->getClientMediaType());
        }
    }

    if ($settings['host']) {
        $mailer->isSMTP();
        $mailer->Host = $settings['host'];
    }

    $mailer->send();
    return $response->withHeader('Location', '/success')->withStatus(302);
});

$app->get('/success', function(Request $request, Response $response) {
    session_start();
    if (!isset($_SESSION['email'])) {
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    $_SESSION = [];
    return $this->view->render($response, 'success.phtml');
});

$app->run();
