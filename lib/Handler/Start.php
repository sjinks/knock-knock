<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Start extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        if (!isset($_SESSION['email'])) {
            return $this->redirect($response, '/');
        }

        $settings = $this->settings();

        return $this->view()->render(
            $response,
            'form.phtml',
            [
                'header_css' => ['https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css'],
                'footer_js'  => [
                    'https://www.google.com/recaptcha/api.js?hl=ru',
                    'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
                    '/js/form.js?4',
                ],
                'recaptcha'  => $settings['recaptcha'],
                'data'       => self::sessionData(),
                'errors'     => self::sessionErrors(),
            ]
        );
    }

    private static function sessionData() : array
    {
        static $empty = [
            'name' => '', 'country' => '', 'address' => '', 'phone' => '', 'desc' => '', 'url' => '', 'present' => '', 'comment' => ''
        ];

        $result = (!empty($_SESSION['data'])) ? $_SESSION['data'] : $empty;
        unset($_SESSION['data']);
        return $result;
    }

    private static function sessionErrors() : array
    {
        $result = empty($_SESSION['errors']) ? [] : $_SESSION['errors'];
        unset($_SESSION['errors']);
        return $result;
    }
}
