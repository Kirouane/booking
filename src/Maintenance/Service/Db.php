<?php
declare(strict_types=1);

namespace Maintenance\Service;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\CreateTable;

/**
 * Class Db
 * @package Maintenance\Service
 */
class Db
{
    /**
     * @var Adapter
     */
    private $db;

    /**
     * Db constructor.
     * @param Adapter $db
     */
    public function __construct(Adapter $db)
    {
        $this->db = $db;
    }

    /**
     *
     * @throws \Zend\Db\Adapter\Exception\InvalidArgumentException
     */
    public function install(): void
    {
        /** @var \Zend\Db\Sql\Ddl\CreateTable $table */
        foreach ($this->getTables() as $tableName => $table) {
            $this->db->query("DROP TABLE IF EXISTS $tableName CASCADE")->execute([]);
            $this->db->query($table->getSqlString($this->db->getPlatform()))->execute([]);
        }
    }

    /**
     * @return \Generator
     */
    public function getTables(): \Generator
    {
        yield 'location' => $this->getLocationTable();
        yield 'resource' => $this->getResourceTable();
        yield 'booking' => $this->getBookingTable();
        yield 'assignment' => $this->getAssignmentTable();
    }

    /**
     * @return CreateTable
     */
    private function getLocationTable(): CreateTable
    {
        $table = new CreateTable('location');
        $table->addColumn(new Ddl\Column\Varchar('name', '255'));
        $table->addColumn(new Ddl\Column\Varchar('timezone', '64'));

        $table->addConstraint(new Ddl\Constraint\PrimaryKey('name'));

        return $table;
    }

    /**
     * @return CreateTable
     */
    private function getResourceTable(): CreateTable
    {
        $table = new CreateTable('resource');
        $table->addColumn(new Ddl\Column\Varchar('name', '255'));

        $table->addConstraint(new Ddl\Constraint\PrimaryKey('name'));

        return $table;
    }

    /**
     * @return CreateTable
     */
    private function getBookingTable(): CreateTable
    {
        $table = new CreateTable('booking');
        $table->addColumn(new Ddl\Column\Varchar('id', '255'));
        $table->addColumn(new Ddl\Column\Timestamp('start'));
        $table->addColumn(new Ddl\Column\Timestamp('end'));
        $table->addColumn(new Ddl\Column\Varchar('resource_name'));
        //$table->addColumn(new Ddl\Column\Varchar('location_name'));

        $table->addConstraint(new Ddl\Constraint\PrimaryKey('id'));
        $table->addConstraint(new Ddl\Constraint\ForeignKey(
            'booking_resource_name_fkey',
            'resource_name',
            'resource',
            'name'
        ));

        /*$table->addConstraint(new Ddl\Constraint\ForeignKey(
            'booking_location_name_fkey',
            'location_name',
            'location',
            'name'
        ));*/

        return $table;
    }

    /**
     * @return CreateTable
     */
    private function getAssignmentTable(): CreateTable
    {
        $table = new CreateTable('assignment');
        $table->addColumn(new Ddl\Column\Varchar('id', '255'));
        $table->addColumn(new Ddl\Column\Timestamp('start'));
        $table->addColumn(new Ddl\Column\Timestamp('end'));
        $table->addColumn(new Ddl\Column\Varchar('resource_name'));
        $table->addColumn(new Ddl\Column\Varchar('location_name'));

        $table->addConstraint(new Ddl\Constraint\PrimaryKey('id'));
        $table->addConstraint(new Ddl\Constraint\ForeignKey(
            'assignment_resource_name_fkey',
            'resource_name',
            'resource',
            'name'
        ));

        $table->addConstraint(new Ddl\Constraint\ForeignKey(
            'assignment_location_name_fkey',
            'location_name',
            'location',
            'name'
        ));

        return $table;
    }
}
