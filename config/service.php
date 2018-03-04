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
        [\Booking\Repository\Database\Booking::class]
    ],

    //services
    'db' =>  [\Zend\Db\Adapter\Adapter::class, [[require 'db.php']]],

    \Booking\Repository\Database\Resource::TABLE_GATEWAY => [
        \Lib\TableGatewayFactory::class,
        [\Zend\Hydrator\ClassMethods::class, \Booking\Model\Resource::class, \Booking\Repository\Database\Resource::TABLE],
        \Lib\Di::FACTORY
    ],
    \Booking\Repository\Database\Resource::class => [
        \Booking\Repository\Database\Resource::class,
        [\Booking\Repository\Database\Resource::TABLE_GATEWAY]
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
