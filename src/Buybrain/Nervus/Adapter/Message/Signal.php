<?php
namespace Buybrain\Nervus\Adapter\Message;

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
     * @return Signal
     */
    public static function fromArray(array $data)
    {
        return new self(array_map([EntityId::class, 'fromArray'], $data['Ids']));
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
