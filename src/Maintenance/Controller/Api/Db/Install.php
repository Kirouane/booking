<?php
declare(strict_types=1);

namespace Maintenance\Controller\Api\Db;

/**
 * Class Install
 * @package Maintenance\Controller\Api\Db
 */
class Install
{
    /**
     * @var \Maintenance\Service\Db
     */
    private $db;

    /**
     * Create constructor.
     * @param \Maintenance\Service\Db $db
     */
    public function __construct(\Maintenance\Service\Db $db)
    {
        $this->db = $db;
    }

    /**
     *
     * @throws \Zend\Db\Adapter\Exception\InvalidArgumentException
     */
    public function __invoke()
    {
        $this->db->install();
    }
}
