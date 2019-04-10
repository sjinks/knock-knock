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
        $as       = $settings['auth0'];

        return $this->view()->render(
            $response,
            'index.phtml',
            [
                'footer_js' => ['https://cdn.auth0.com/js/lock/11.14.1/lock.min.js', '/js/login.js?v=4'],
                'auth0'     => $as,
            ]
        );
    }
}
