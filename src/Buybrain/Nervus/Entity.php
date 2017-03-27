<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Util\Objects;
use Buybrain\Nervus\Util\Typed;
use JsonSerializable;

/**
 * Business object that can be synced by the nervus system. Entities have an ID (which also contains the type of the
 * entity), data, and a 'deleted' flag. The data is encoded as a string (a byte array technically) and the encoding
 * scheme is up to the client application.
 * 
 * @see EntityId
 */
class Entity implements JsonSerializable, Typed
{
    /** @var EntityId */
    private $id;
    /** @var string */
    private $data;
    /** @var bool */
    private $deleted;

    /**
     * @param EntityId $id
     * @param string $data
     * @param bool $deleted
     */
    public function __construct(EntityId $id, $data, $deleted = false)
    {
        $this->id = $id;
        $this->data = $data;
        $this->deleted = $deleted;
    }

    /**
     * @return EntityId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * return array
     */
    public function jsonSerialize()
    {
        return [
            'Id' => $this->id,
            'Data' => $this->data,
            'Deleted' => $this->deleted,
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->id->getType();
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param array $data
     * @return Entity
     */
    public static function fromArray(array $data)
    {
        return new self(
            EntityId::fromArray($data['Id']),
            Objects::toPrimitiveOrStruct($data['Data']),
            $data['Deleted']
        );
    }
}
