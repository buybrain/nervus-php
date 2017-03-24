<?php
namespace Buybrain\Nervus\Util;

class Arrays
{
    /**
     * Concatenates multiple arrays into a single array
     * 
     * @param array[] $arrays
     * @return array
     */
    public static function flatten(array $arrays)
    {
        return call_user_func_array('array_merge', $arrays);
    }
}
