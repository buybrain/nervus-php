<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;
use JsonSerializable;

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
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'Ids' => $this->ids,
        ];
    }
}
