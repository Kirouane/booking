<?php
declare(strict_types=1);
return [
    'booking.resource.create' => \Booking\Controller\Api\Resource\CreateAction::class,
    'booking.resource.list' => \Booking\Controller\Api\Resource\ListAction::class,
    'booking.booking.create' => \Booking\Controller\Api\Booking\CreateAction::class,

    'maintenance.db.install' => \Maintenance\Controller\Api\Db\Install::class
];
