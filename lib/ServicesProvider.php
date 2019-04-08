<?php

namespace WildWolf;

use Auth0\SDK\Auth0;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReCaptcha\ReCaptcha;
use Slim\Views\PhpRenderer;

class ServicesProvider
{
    public function register(\ArrayAccess $container)
    {
        $this->registerServices($container);
        $this->registerHandlers($container);
    }

    private function registerServices(\ArrayAccess $container)
    {
        $container['view'] = new PhpRenderer(__DIR__ . '/../templates');

        $container['auth0'] = function (ContainerInterface $container) {
            return self::auth0($container);
        };

        $container['recaptcha'] = function (ContainerInterface $container) {
            return self::reCaptcha($container);
        };

        $container['mailer']    = function (ContainerInterface $container) {
            return self::phpMailer($container);
        };
    }

    private function registerHandlers(\ArrayAccess $container)
    {
        $container['notFoundHandler'] = function (ContainerInterface $container) {
            return function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
                return $container->get('view')->render($response, '404.phtml')->withStatus(404);
            };
        };

        $container['notAllowedHandler'] = function (/** @scrutinizer ignore-unused */ ContainerInterface $container) {
            return function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response->withHeader('Location', '/')->withStatus(302);
            };
        };

        $error_handler = function (ContainerInterface $container) {
            return function (ServerRequestInterface $request, ResponseInterface $response, \Throwable $error) use ($container) {
                error_log($error);
                return $container->get('view')->render($response, '500.phtml')->withStatus(500);
            };
        };

        $container['phpErrorHandler'] = $error_handler;
        $container['errorHandler']    = $error_handler;
    }

    public static function auth0(ContainerInterface $container) : Auth0
    {
        $settings = $container->get('settings');
        $as       = $settings['auth0'];

        $auth0    = new Auth0([
            'domain'               => $as['domain'],
            'client_id'             => $as['clientid'],
            'client_secret'         => $as['clientsecret'],
            'redirect_uri'          => $as['login'],
            'audience'              => 'https://' . $as['domain'] . '/userinfo',
            'scope'                 => 'openid profile email',
            'persist_id_token'      => true,
            'persist_access_token'  => true,
            'persist_refresh_token' => true,
        ]);

        return $auth0;
    }

    public static function reCaptcha(ContainerInterface $container) : ReCaptcha
    {
        $settings  = $container->get('settings');
        $rc        = $settings['recaptcha'];
        $recaptcha = new ReCaptcha($rc['secret']);
        return $recaptcha;
    }

    public static function phpMailer(ContainerInterface $container) : \PHPMailer
    {
        $settings = $container->get('settings');
        $pm       = $settings['mailer'];

        $mailer           = new \PHPMailer();
        $mailer->From     = $pm['from'];
        $mailer->FromName = '';
        $mailer->Sender   = $pm['sender'] ?? $pm['from'];
        $mailer->CharSet  = 'utf-8';
        $mailer->addAddress($pm['to']);

        if ($pm['host']) {
            $mailer->isSMTP();
            $mailer->Host = $pm['host'];
            $mailer->Port = $pm['port'] ?? 25;
            $mailer->SMTPSecure = $pm['secure'] ?? '';
            $mailer->Helo = $pm['helo'] ?? '';

            $mailer->SMTPAuth = $pm['smtpauth'];
            $mailer->Username = $pm['username'];
            $mailer->Password = $pm['password'];
        }

        return $mailer;
    }
}
