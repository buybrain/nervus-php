<?php
namespace Buybrain\Nervus\Adapter\Message;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Buybrain\Nervus\EntityId;
use JsonSerializable;

/**
 * Request message for reading a list of entities based on their IDs
 */
class ReadRequest implements JsonSerializable
{
    /** @var EntityId[] */
    private $ids;

    /**
     * @param EntityId[] $ids
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return EntityId[]
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param array $data
     * @param StructMapper $mapper
     * @return ReadRequest
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        $ids = array_map(function ($id) use ($mapper) {
            return $mapper->unmap($id, EntityId::class);
        }, $data['ids']);

        return new self($ids);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return ['ids' => $this->ids];
    }
}
