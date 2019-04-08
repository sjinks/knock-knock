<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Logout extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        /**
         * @var \Auth0\SDK\Auth0
         */
        $auth0    = $this->container()->get('auth0');
        $settings = $this->container()->get('settings');
        $as       = $settings['auth0'];

        $auth0->logout();
        $url = sprintf('https://%s/v2/logout?client_id=%s&returnTo=%s', $as['domain'], $as['clientid'], $as['logout']);
        session_destroy();
        return $this->redirect($response, $url);
    }
}
