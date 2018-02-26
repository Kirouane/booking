<?php
require '../vendor/autoload.php';

$di = new \Lib\Di(require '../config/service.php');

// Create the routing dispatcher
$fastRouteDispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($di) {
    $r->addRoute('POST', '/rpc', new \Lib\RpcRouter($di, require '../config/route.php'));
});

$dispatcher = new \Middlewares\Utils\Dispatcher([
    new Middlewares\JsonPayload(),
    new Middlewares\FastRoute($fastRouteDispatcher),
    new Middlewares\RequestHandler(),
]);

(new \Zend\Diactoros\Response\SapiEmitter())->emit(
    $dispatcher->dispatch(\Zend\Diactoros\ServerRequestFactory::fromGlobals())
);
