<?php
namespace Buybrain\Nervus\Adapter\Message;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Buybrain\Nervus\Entity;
use JsonSerializable;

/**
 * Request message for writing a list of entities
 */
class WriteRequest implements JsonSerializable
{
    /** @var Entity[] */
    private $entities;

    /**
     * @param Entity[] $entities
     */
    public function __construct(array $entities)
    {
        $this->entities = $entities;
    }

    /**
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param array $data
     * @param StructMapper $mapper
     * @return WriteRequest
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        $entities = array_map(function ($id) use ($mapper) {
            return $mapper->unmap($id, Entity::class);
        }, $data['entities']);
        return new self($entities);
    }

    public function jsonSerialize(): array
    {
        return [
            'entities' => $this->entities,
        ];
    }
}
