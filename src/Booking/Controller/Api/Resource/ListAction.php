<?php
declare(strict_types=1);

namespace Booking\Controller\Api\Resource;

use Booking\Repository\Database\Resource;
use Booking\Repository\Database\Resource as ResourceRepository;

/**
 * Class ListAction
 * @package Booking\Controller\Api\Resource
 */
class ListAction
{
    /**
     * @var ResourceRepository
     */
    private $repository;

    /**
     * ListAction constructor.
     * @param ResourceRepository $resource
     */
    public function __construct(ResourceRepository $resource)
    {
        $this->repository = $resource;
    }

    /**
     * @param array $prams
     * @return array
     */
    public function __invoke(array $prams)
    {
        $rows = $this->repository->findAll();
        $data = [];

        foreach ($rows as $row) {
            $data[] = $row;
        }

        return [
            'data' => $data
        ];
    }
}
