<?php

require 'vendor/autoload.php';

$di = new \Lib\Di(require 'config/service.php');
$cli = new \Lib\RabbitMq\Server($di->get('rabbitmq'), 'booking.topic');

$cli->listen('booking', ['#'], function ($msg) {
    echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
});
