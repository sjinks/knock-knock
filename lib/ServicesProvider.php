<?php

namespace WildWolf;

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

        $container['accountkit'] = function (ContainerInterface $container) {
            return self::accountKit($container);
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

    public static function accountKit(ContainerInterface $container) : AccountKit
    {
        $settings = $container->get('settings');
        $ak       = $settings['accountkit'];

        $accountkit = new AccountKit($ak['appid'], $ak['secret']);
        $accountkit->setApiVersion($ak['apiver']);
        return $accountkit;
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
        $mailer->CharSet  = 'utf-8';
        $mailer->addAddress($pm['to']);

        if ($settings['host']) {
            $mailer->isSMTP();
            $mailer->Host = $pm['host'];
        }

        return $mailer;
    }
}
