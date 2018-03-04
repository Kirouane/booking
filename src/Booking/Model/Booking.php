<?php
declare(strict_types=1);

namespace Booking\Model;

/**
 * Class Resource
 * @package Booking\Model
 */
class Booking implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $end;

    /**
     * @var string
     */
    private $resourceName;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Booking
     */
    public function setId(string $id): Booking
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @param string $start
     * @return Booking
     */
    public function setStart(string $start): Booking
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @param string $end
     * @return Booking
     */
    public function setEnd(string $end): Booking
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * @param string $resourceName
     * @return Booking
     */
    public function setResourceName(string $resourceName): Booking
    {
        $this->resourceName = $resourceName;
        return $this;
    }



    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'start' => $this->getStart(),
            'end' => $this->getEnd(),
            'resource_name' => $this->getResourceName()
        ];
    }
}
