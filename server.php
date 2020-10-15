<?php

require __DIR__ . '/vendor/autoload.php';

use App\Products\Controller\ProductsController;
use App\Router;
use App\Users\Controller\UsersController;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Server;
use React\Http\Message\Response;

$loop = \React\EventLoop\Factory::create();

$routes = new RouteCollector(new Std(), new GroupCountBased());
$routes->get('/', function (ServerRequestInterface $request) {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                json_encode(['message' => 'Hello world'])
            ]
        );
    });
$routes->get('/users', new UsersController());
$routes->get('/products', new ProductsController());

$server = new Server($loop, new Router($routes));
$socket = new \React\Socket\Server(8080, $loop);
$server->listen($socket);

$server->on(
    'error',
    function (\Throwable $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }
);

$loop->run();
