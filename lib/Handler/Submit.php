<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Slim\Http\Environment;
use ReCaptcha\ReCaptcha;

class Submit extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        if (!isset($_SESSION['email'])) {
            return $this->redirect($response, '/');
        }

        $post              = $request->getParsedBody();
        $data              = self::getData($post);
        $_SESSION['data']  = $data;
        $data['recaptcha'] = $this->checkReCaptcha($post);
        $errors            = self::validate($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect($response, '/');
        }

        $message = $this->buildMessage($data);
        $this->sendEmail($request, $message);

        return $this->redirect($response, '/success');
    }

    private function checkReCaptcha(array $post) : bool
    {
        $container = $this->container();

        /**
         * @var ReCaptcha $recaptcha
         */
        $recaptcha = $container->get('recaptcha');

        /**
         * @var Environment $env
         */
        $env       = $container->get('environment');

        $grr       = $post['g-recaptcha-response'] ?? null;
        $ip        = $env->get('REMOTE_ADDR');
        $result    = $recaptcha->verify($grr, $ip);
        return $result->isSuccess();
    }

    private static function getData(array $post) : array
    {
        $data = [];
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

        return $data;
    }

    private static function validate(array $data) : array
    {
        $errors = [];

        if (empty($data['recaptcha'])) {
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

        return $errors;
    }

    private function buildMessage(array $data) : string
    {
        /**
         * @var Environment $env
         */
        $env      = $this->container()->get('environment');
        $ip       = $env->get('REMOTE_ADDR');
        $message  = '';

        if ($data['present']) $message .= "Обновление информации: {$data['url']}\n";

        $message .= "ФИО: {$data['name']}\n";
        $message .= "Страна: {$data['country']}\n";

        if ($data['address']) $message .= "Адрес: {$data['address']}\n";
        if ($data['phone'])   $message .= "Телефон: {$data['phone']}\n";

        $message .= "Описание:\n{$data['desc']}\n\n";

        if ($data['comment']) $message .= "Примечание: {$data['comment']}\n";

        $message .= "-----\nОтправитель: {$_SESSION['email']}\n";
        $message .= "IP: {$ip}\n";

        return $message;
    }

    private function sendEmail(ServerRequestInterface $request, string $message)
    {
        $settings = $this->settings()['mailer'];

        $mailer = new \PHPMailer();
        $mailer->From = $settings['from'];
        $mailer->addReplyTo($_SESSION['email']);
        $mailer->addAddress($settings['to']);
        $mailer->FromName = '';
        $mailer->Subject  = 'Запрос на внесение в Чистилище';
        $mailer->Body     = $message;
        $mailer->CharSet  = 'utf-8';

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
    }
}
