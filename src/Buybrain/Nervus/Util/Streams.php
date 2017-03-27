<?php
namespace Buybrain\Nervus\Util;

use InvalidArgumentException;

/**
 * Utility functions for dealing with streams
 */
class Streams
{
    /**
     * @param mixed $stream
     * @return resource
     */
    public static function assertStream($stream)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new InvalidArgumentException(sprintf(
                'Failed to assert that argument is a stream resource, got a %s instead',
                gettype($stream)
            ));
        }
        return $stream;
    }
}
