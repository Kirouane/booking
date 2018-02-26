<?php
declare(strict_types=1);

namespace Booking\Controller\Api\Resource;

use Booking\Model\Resource;
use Booking\Repository\Database\Resource as ResourceRepository;

/**
 * Class CreateAction
 * @package Booking\Controller\Api\Resource
 */
class CreateAction
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
     * @param array $params
     * @return array
     */
    public function __invoke(array $params)
    {
        $resource = new Resource();
        $resource->setName($params['name']);

        return [
            'data' => $this->repository->insert($resource)
        ];
    }
}
