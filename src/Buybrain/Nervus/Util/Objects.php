<?php
namespace Buybrain\Nervus\Util;

use InvalidArgumentException;
use JsonSerializable;
use stdClass;

/**
 * Utility functions for dealing with objects
 */
class Objects
{
    /**
     * @param mixed $input
     * @return mixed
     */
    public static function toPrimitiveOrStruct($input)
    {
        if ($input instanceof stdClass) {
            $input = (array)$input;
        }
        if (is_object($input)) {
            if (!$input instanceof JsonSerializable) {
                throw new InvalidArgumentException('Objects must implement JsonSerializable');
            }
            $input = $input->jsonSerialize();
        }
        if (is_array($input)) {
            foreach ($input as $i => $value) {
                $input[$i] = self::toPrimitiveOrStruct($value);
            }
        }
        return $input;
    }
}
