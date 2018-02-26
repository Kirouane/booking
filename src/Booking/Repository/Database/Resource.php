<?php
declare(strict_types=1);

namespace Booking\Repository\Database;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class Resource
 * @package Booking\Repository\Database
 */
class Resource
{
    public const TABLE_GATEWAY = self::class . 'TABLE_GATEWAY';

    public const TABLE = 'resource';

    /**
     * @var TableGateway
     */
    private $tableGateway;

    /**
     * Resource constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return ResultSetInterface
     * @throws \RuntimeException
     */
    public function findAll(): ResultSetInterface
    {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();
        return $this->tableGateway->selectWith($select);
    }

    /**
     * @param $name
     * @return \Booking\Model\Resource|null
     * @throws \RuntimeException
     */
    public function findOneByName($name): ?\Booking\Model\Resource
    {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();
        $select->where->equalTo('name', $name);
        return $this->tableGateway->selectWith($select)->current();
    }

    /**
     * @param \Booking\Model\Resource $resource
     * @return \Booking\Model\Resource|null
     */
    public function insert(\Booking\Model\Resource $resource): ?\Booking\Model\Resource
    {
        $this->tableGateway->insert(
            $this->tableGateway->getResultSetPrototype()->getHydrator()->extract($resource)
        );
        return $this->findOneByName($resource->getName());
    }
}
