<?php

namespace WildWolf;

use Slim\App;
use Slim\Container;
use WildWolf\Handler\Index;
use WildWolf\Handler\Login;
use WildWolf\Handler\Logout;
use WildWolf\Handler\Heartbeat;
use WildWolf\Handler\Start;
use WildWolf\Handler\Success;
use WildWolf\Handler\Submit;
use WildWolf\Handler\Verify;
use WildWolf\Middleware\CloudflareIpRewrite;

class Application extends App
{
    public function initialize()
    {
        session_start();
        $this->setUpDI($this->getContainer());
        $this->setUpMiddleware();
        $this->setUpRoutes();
    }

    protected function setUpDI(Container $c)
    {
        $provider = new ServicesProvider();
        $provider->register($c);
    }

    protected function setUpMiddleware()
    {
        $this->add(new CloudflareIpRewrite($this));
    }

    protected function setUpRoutes()
    {
        $this->get('/',           new Index($this));
        $this->get('/start',      new Start($this));
        $this->get('/success',    new Success($this));
        $this->get('/login',      new Login($this));
        $this->get('/verify',     new Verify($this));
        $this->get('/logout',     new Logout($this));
        $this->post('/heartbeat', new Heartbeat($this));
        $this->post('/submit',    new Submit($this));
    }
}
