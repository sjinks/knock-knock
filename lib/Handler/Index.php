<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Index extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        if (isset($_SESSION['email'])) {
            return $this->redirect($response, '/start');
        }

        $settings = $this->settings();

        return $this->view()->render(
            $response,
            'index.phtml',
            [
                'accountkit' => $settings['accountkit'],
                'header_js'  => ['https://sdk.accountkit.com/ru_RU/sdk.js'],
                'footer_js'  => ['/js/login.js'],
            ]
        );
    }
}
