<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Verify extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        /**
         * @var \Auth0\SDK\Auth0
         */
        $auth0 = $this->container()->get('auth0');
        $auth0->login();
    }
}
