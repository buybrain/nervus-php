<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/**
 * Interface for classes that can read a list of entities based on their IDs
 */
interface Reader
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids);

    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes();
}
