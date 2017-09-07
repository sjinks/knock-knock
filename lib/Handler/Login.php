<?php

namespace WildWolf\Handler;

use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;

class Login extends BaseHandler
{
    protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface
    {
        $data = $request->getParsedBody();
        $code = $data['code'] ?? null;

        try {
            /**
             * @var \WildWolf\AccountKit $kit
             */
            $kit = $this->container()->get('accountkit');
            $d1  = $kit->getAccessToken($code);
            $d2  = $kit->validateAccessToken($d1->access_token);

            $_SESSION['email'] = $d2->email->address;
            return $this->redirect($response, '/start');
        }
        catch (\Exception $e) {
            return $this->view()->render($response, 'loginfailed.phtml');
        }
    }
}
