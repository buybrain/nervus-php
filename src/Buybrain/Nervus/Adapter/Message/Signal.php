<?php
namespace Buybrain\Nervus\Adapter\Message;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Buybrain\Nervus\EntityId;
use JsonSerializable;

/**
 * Collection of entity IDs indicating that the referenced entities (might) have changed and should be synced
 */
class Signal implements JsonSerializable
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
     * @return Signal
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        return new self(array_map(function ($id) use ($mapper) {
            return $mapper->unmap($id, EntityId::class);
        }, $data['ids']));
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'ids' => $this->ids,
        ];
    }
}
