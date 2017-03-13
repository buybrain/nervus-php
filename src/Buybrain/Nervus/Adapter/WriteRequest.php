<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use JsonSerializable;

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
     * @return WriteRequest
     */
    public static function fromArray(array $data)
    {
        $entities = array_map([Entity::class, 'fromArray'], $data['Entities']);
        return new self($entities);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'Entities' => $this->entities,
        ];
    }
}