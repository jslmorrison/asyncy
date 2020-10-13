<?php

require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Server;
use React\Http\Message\Response;

$loop = \React\EventLoop\Factory::create();

$dispatcher = FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $routes) {
    $routes->get('/', function (ServerRequestInterface $request) {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['message' => 'Hello world'])
            ]
        );
    });
    $routes->get('/users', function (ServerRequestInterface $request) {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['message' => 'Response from GET users collection'])
            ]
        );
    });
    $routes->get('/products', function (ServerRequestInterface $request) {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['message' => 'Response from GET products collection'])
            ]
        );
    });
});

$server = new Server($loop, function (ServerRequestInterface $request) use ($dispatcher) {

    $routeInfo = $dispatcher->dispatch(
        $request->getMethod(),
        $request->getUri()->getPath()
    );

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            return new Response(404, ['Content-Type' => 'text/plain'], 'Not found');
        break;
        case FastRoute\Dispatcher::FOUND:
            return $routeInfo[1]($request);
        break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            return new Response(405, ['Content-Type' => 'text/plain'], 'Not allowed');
        break;
        default:
            throw new \RuntimeException('Oops - routing is broken');
        break;
    }
});

$socket = new \React\Socket\Server(8080, $loop);
$server->listen($socket);

$server->on(
    'error',
    function (\Throwable $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }
);

$loop->run();
