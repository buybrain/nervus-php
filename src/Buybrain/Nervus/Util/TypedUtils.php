<?php
namespace Buybrain\Nervus\Util;

/**
 * Utility functions for dealing with typed objects
 */
class TypedUtils
{
    /**
     * Get the unique types of a list of typed objects
     *
     * @param Typed[] $objects
     * @return string[]
     */
    public static function uniqueTypes(array $objects)
    {
        return array_unique(array_map(function (Typed $obj) {
            return $obj->getType();
        }, $objects));
    }

    /**
     * Group typed objects by their type
     *
     * @param Typed[] $objects
     * @return Typed[][] map indexed by types and lists of objects as values
     */
    public static function groupByType(array $objects)
    {
        $perType = [];
        foreach ($objects as $object) {
            $type = $object->getType();
            if (!isset($perType[$type])) {
                $perType[$type] = [];
            }
            $perType[$type][] = $object;
        }
        return $perType;
    }
}
