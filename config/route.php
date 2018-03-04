<?php
declare(strict_types=1);
return [

    //rpc
    'booking.resource.create' => \Booking\Controller\Api\Resource\CreateAction::class,
    'booking.resource.list' => \Booking\Controller\Api\Resource\ListAction::class,
    'booking.booking.create' => \Booking\Controller\Api\Booking\CreateAction::class,

    'maintenance.db.install' => \Maintenance\Controller\Api\Db\Install::class,

    //amq
    'booking.booking.created' => \Booking\Controller\Event\Booking\CreatedAction::class,

];
