<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Util\Typed;
use JsonSerializable;

/**
 * Identifier of an Entity. Contains the type of entity and an ID that's unique among all entities of the same type.
 *
 * @see Entity
 */
class EntityId implements JsonSerializable, Typed
{
    /** @var string */
    private $type;
    /** @var string */
    private $id;

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct($type, $id)
    {
        $this->type = (string)$type;
        $this->id = (string)$id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
        ];
    }

    /**
     * @param array $data
     * @return EntityId
     */
    public static function fromArray(array $data)
    {
        return new self($data['type'], $data['id']);
    }
}
