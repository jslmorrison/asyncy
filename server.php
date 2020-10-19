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
use WyriHaximus\React\PSR3\Stdio\StdioLogger;

$loop = \React\EventLoop\Factory::create();

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->addDefinitions(
    [
        'user.finder' => \DI\autowire(\App\Users\Finder\MysqlUserFinder::class)
    ]
);
$container = $containerBuilder->build();

$logger = StdioLogger::create($loop)->withNewLine(true);

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
$routes->get('/users', new UsersController($container->get('user.finder')));
$routes->get('/products', new ProductsController());

$server = new Server($loop, new Router($routes));
$socket = new \React\Socket\Server(8080, $loop);
$server->listen($socket);

$server->on(
    'error',
    function (\Throwable $e) use ($logger) {
        $logger->error('Sorry Dave, i cant do that.');
    }
);

$logger->info('Server started, listening on ' . $socket->getAddress());
$loop->run();
