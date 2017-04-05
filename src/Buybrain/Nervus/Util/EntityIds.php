<?php
namespace Buybrain\Nervus\Util;

use Buybrain\Nervus\EntityId;

/**
 * Utility for working with entity IDs
 */
class EntityIds
{
    /**
     * Extract the real IDs from a list of EntityId objects, discarding the type information
     *
     * @param EntityId[] $ids
     * @return string[]
     */
    public static function extractIds(array $ids)
    {
        return array_map(function (EntityId $id) {
            return $id->getId();
        }, $ids);
    }
}