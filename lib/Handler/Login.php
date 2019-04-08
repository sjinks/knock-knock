<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Login extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        /**
         * @var \Auth0\SDK\Auth0
         */
        $auth0  = $this->container()->get('auth0');
        $method = $request->getMethod();
        $params = $request->getQueryParams();
        if (!empty($params['code'])) {
            try {
                $userInfo = $auth0->getUser();
                $_SESSION['email'] = $userInfo['email'] ?? $userInfo['name'];
                return $this->redirect($response, '/start');
            }
            catch (\Exception $e) {
            }
        }
        // return $this->view()->render($response, 'loginfailed.phtml');
        $auth0->login();
    }
}
