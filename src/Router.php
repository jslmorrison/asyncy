<?php

namespace App;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;

final class Router
{
    private $dispatcher;

    public function __construct(RouteCollector $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->getData());
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $routeInfo = $this->dispatcher
            ->dispatch(
                $request->getMethod(),
                $request->getUri()->getPath()
            );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response(404, ['Content-Type' => 'text/plain'], 'Not found');
            break;
            case Dispatcher::FOUND:
                return $routeInfo[1]($request);
            break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(405, ['Content-Type' => 'text/plain'], 'Not allowed');
            break;
            default:
                throw new \RuntimeException('Oops - routing is broken');
            break;
        }
    }
}
