<?php
namespace Buybrain\Nervus;

use JsonSerializable;

class Entity implements JsonSerializable
{
    /** @var EntityId */
    private $id;
    /** @var string */
    private $data;

    /**
     * @param EntityId $id
     * @param string $data
     */
    public function __construct(EntityId $id, $data)
    {
        $this->id = $id;
        $this->data = $data;
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
    function jsonSerialize()
    {
        return [
            'Id' => $this->id,
            'Data' => base64_encode($this->data),
        ];
    }
}
