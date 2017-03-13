<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;
use JsonSerializable;

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
     * @return ReadRequest
     */
    public static function fromArray(array $data)
    {
        $ids = array_map([EntityId::class, 'fromArray'], $data['Ids']);
        return new self($ids);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return ['Ids' => $this->ids];
    }
}
