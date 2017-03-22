<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Util\Typed;
use JsonSerializable;

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
        $this->type = $type;
        $this->id = $id;
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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'Type' => $this->type,
            'Id' => $this->id
        ];
    }

    /**
     * @param array $data
     * @return EntityId
     */
    public static function fromArray(array $data)
    {
        return new self($data['Type'], $data['Id']);
    }
}
