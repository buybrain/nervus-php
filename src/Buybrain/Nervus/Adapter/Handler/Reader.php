<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/**
 * Interface for adapter handlers that can read a list of entities based on their IDs
 */
interface Reader extends TypedHandler
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids);
}
