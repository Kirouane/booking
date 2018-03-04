<?php
return [
    /**Booking **/
    //controllers
    \Booking\Controller\Api\Resource\CreateAction::class => [
        \Booking\Controller\Api\Resource\CreateAction::class,
        [\Booking\Repository\Database\Resource::class]
    ],
    \Booking\Controller\Api\Resource\ListAction::class => [
        \Booking\Controller\Api\Resource\ListAction::class,
        [\Booking\Repository\Database\Resource::class]
    ],

    \Booking\Controller\Api\Booking\CreateAction::class => [
        \Booking\Controller\Api\Booking\CreateAction::class,
        [\Booking\Service\Booking\Create::class]
    ],

    //event
    \Booking\Controller\Event\Booking\CreatedAction::class => \Booking\Controller\Event\Booking\CreatedAction::class,

    //services
    'db' =>  [\Zend\Db\Adapter\Adapter::class, [[require 'db.php']]],
    'rabbitmq' => [PhpAmqpLib\Connection\AMQPStreamConnection::class, [['rabbitmq'], [5672], ['guest'], ['guest']]],
    \Lib\RabbitMq\Client::class => [
        \Lib\RabbitMq\Client::class,
        ['rabbitmq', ['booking.topic']]
    ],
    \Lib\RabbitMq\Server::class => [
        \Lib\RabbitMq\Server::class,
        ['rabbitmq', ['booking.topic']]
    ],

    \Booking\Repository\Database\Resource::TABLE_GATEWAY => [
        \Lib\TableGatewayFactory::class,
        [\Zend\Hydrator\ClassMethods::class, \Booking\Model\Resource::class, \Booking\Repository\Database\Resource::TABLE],
        \Lib\Di::FACTORY
    ],
    \Booking\Repository\Database\Resource::class => [
        \Booking\Repository\Database\Resource::class,
        [\Booking\Repository\Database\Resource::TABLE_GATEWAY]
    ],

    \Booking\Service\Booking\Create::class => [
        \Booking\Service\Booking\Create::class,
        [\Booking\Repository\Database\Booking::class, \Lib\RabbitMq\Client::class]
    ],
    \Booking\Repository\Database\Booking::TABLE_GATEWAY => [
        \Lib\TableGatewayFactory::class,
        [\Zend\Hydrator\ClassMethods::class, \Booking\Model\Booking::class, \Booking\Repository\Database\Booking::TABLE],
        \Lib\Di::FACTORY
    ],
    \Booking\Repository\Database\Booking::class => [
        \Booking\Repository\Database\Booking::class,
        [\Booking\Repository\Database\Booking::TABLE_GATEWAY]
    ],


    /**Maintenance **/
    \Maintenance\Controller\Api\Db\Install::class => [
        \Maintenance\Controller\Api\Db\Install::class,
        [\Maintenance\Service\Db::class]
    ],

    \Maintenance\Service\Db::class =>  [\Maintenance\Service\Db::class, ['db']]

];
