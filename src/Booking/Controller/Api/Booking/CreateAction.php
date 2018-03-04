<?php
declare(strict_types=1);

namespace Booking\Controller\Api\Booking;

use Booking\Model\Booking;
use Booking\Repository\Database\Booking as BookingRepository;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateAction
 * @package Booking\Controller\Api\Booking
 */
class CreateAction
{
    /**
     * @var BookingRepository
     */
    private $repository;

    /**
     * ListAction constructor.
     * @param BookingRepository $resource
     */
    public function __construct(BookingRepository $resource)
    {
        $this->repository = $resource;
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
            'data' => $this->repository->insert($booking)
        ];
    }
}
