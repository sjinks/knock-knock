<?php

namespace WildWolf\Middleware;

use CloudFlare\IpRewrite;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

class CloudflareIpRewrite
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $cf = new IpRewrite();
        if ($cf->isCloudFlare()) {
            /**
             * @var \Slim\Http\Environment $env
             */
            $env = $this->app->getContainer()['environment'];
            $env['cf.original_ip'] = $cf->getOriginalIP();
            $env['REMOTE_ADDR']    = $cf->getRewrittenIP();
        }

        return $next($request, $response);
    }
}
