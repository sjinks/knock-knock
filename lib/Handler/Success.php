<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Success extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        if (!isset($_SESSION['email'])) {
            return $this->redirect($response, '/');
        }

        $_SESSION = [];
        return $this->view()->render($response, 'success.phtml');
    }
}
