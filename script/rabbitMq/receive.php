<?php
declare(strict_types=1);

require 'vendor/autoload.php';

$di = new \Lib\Di(require 'config/service.php');


/** @var \Lib\RabbitMq\Server $server */
$server = $di->get(\Lib\RabbitMq\Server::class);

$server->listen('booking', ['#'], new \Lib\AmqRouter($di, require 'config/route.php'));
