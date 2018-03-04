<?php
declare(strict_types=1);

namespace Booking\Controller\Event\Booking;

use Booking\Model\Booking;
use Booking\Service\Booking\Create as BookingService;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateAction
 * @package Booking\Controller\Api\Booking
 */
class CreatedAction
{

    /**
     * @param array $params
     * @return array
     */
    public function __invoke(array $params)
    {
        return [];
    }
}
