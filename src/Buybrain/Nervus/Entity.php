<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
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
        $this->data = (string)$data;
        $this->deleted = (bool)$deleted;
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
            'id' => $this->id,
            'data' => $this->data,
            'deleted' => $this->deleted,
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
     * @param StructMapper $mapper
     * @return Entity
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        return new self(
            $mapper->unmap($data['id'], EntityId::class),
            $data['data'],
            $data['deleted']
        );
    }
}
