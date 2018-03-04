<?php
declare(strict_types=1);

namespace Booking\Repository\Database;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class Resource
 * @package Booking\Repository\Database
 */
class Booking
{
    public const TABLE_GATEWAY = self::class . 'TABLE_GATEWAY';

    public const TABLE = 'booking';

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
    public function findOneById($id): ?\Booking\Model\Booking
    {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();
        $select->where->equalTo('id', $id);
        return $this->tableGateway->selectWith($select)->current();
    }

    /**
     * @param \Booking\Model\Resource $resource
     * @return \Booking\Model\Resource|null
     */
    public function insert(\Booking\Model\Booking $booking): ?\Booking\Model\Booking
    {
        $this->tableGateway->insert(
            $this->tableGateway->getResultSetPrototype()->getHydrator()->extract($booking)
        );
        return $this->findOneById($booking->getId());
    }
}
