<?php
declare(strict_types=1);

namespace Booking\Controller\Api\Booking;

use Booking\Model\Booking;
use Booking\Service\Booking\Create as BookingService;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateAction
 * @package Booking\Controller\Api\Booking
 */
class CreateAction
{
    /**
     * @var BookingService
     */
    private $service;

    /**
     * ListAction constructor.
     * @param BookingService $resource
     */
    public function __construct(BookingService $resource)
    {
        $this->service = $resource;
    }

    /**
     * @param array $params
     * @return array
     */
    public function __invoke(array $params)
    {
        $booking = new Booking();
        $booking->setId(Uuid::uuid4()->toString());
        $booking->setStart($params['start']);
        $booking->setEnd($params['end']);
        $booking->setResourceName($params['resource_name']);
        return [
            'data' => ($this->service)($booking)
        ];
    }
}
