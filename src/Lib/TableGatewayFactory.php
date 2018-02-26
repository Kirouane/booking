<?php
namespace Lib;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class TableGatewayFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $hydratorClass;

    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    private $tableName;

    /**
     * TableGatewayFactory constructor.
     * @param string $hydratorClass
     * @param string $modelClass
     * @param string $tableName
     */
    public function __construct(string $hydratorClass, string $modelClass, string $tableName)
    {
        $this->hydratorClass = $hydratorClass;
        $this->modelClass = $modelClass;
        $this->tableName = $tableName;
    }


    /**
     * @param Di $di
     * @return TableGateway
     * @throws \DomainException
     * @throws \Zend\Db\TableGateway\Exception\InvalidArgumentException
     */
    public function createService(\Lib\Di $di)
    {
        return new TableGateway(
            $this->tableName,
            $di->get('db'),
            null,
            $resultSetPrototype = new HydratingResultSet($di->get($this->hydratorClass), new $this->modelClass())
        );
    }
}
