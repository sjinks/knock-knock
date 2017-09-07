<?php

namespace WildWolf\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Slim\App;
use Slim\Views\PhpRenderer;

abstract class BaseHandler
{
    /**
     * @var \Slim\App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = null)
    {
        return $this->run($request, $response, $args);
    }

    protected function container() : ContainerInterface
    {
        return $this->app->getContainer();
    }

    protected function settings()
    {
        return $this->container()->get('settings');
    }

    protected function view() : PhpRenderer
    {
        return $this->container()->get('view');
    }

    protected function redirect(ResponseInterface $response, string $location)
    {
        return $response->withHeader('Location', $location)->withStatus(302);
    }

    abstract protected function run(ServerRequestInterface $request, ResponseInterface $response, array $args = null) : ResponseInterface;
}
