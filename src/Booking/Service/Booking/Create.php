<?php
declare(strict_types=1);

namespace Booking\Service\Booking;

use Booking\Model\Booking;
use Lib\RabbitMq\Client;

/**
 * Class Create
 * @package Booking\Service\Booking
 */
class Create
{
    /**
     * @var \Booking\Repository\Database\Booking
     */
    private $repository;

    /**
     * @var Client
     */
    private $rabbitMq;

    /**
     * Create constructor.
     * @param \Booking\Repository\Database\Booking $repository
     * @param Client $rabbitMq
     */
    public function __construct(\Booking\Repository\Database\Booking $repository, Client $rabbitMq)
    {
        $this->repository = $repository;
        $this->rabbitMq = $rabbitMq;
    }

    /**
     * @param Booking $booking
     */
    public function __invoke(Booking $booking)
    {
        $booking = $this->repository->insert($booking);
        $this->rabbitMq->send('booking.booking.created', json_encode($booking));
        return $booking;
    }
}
