<?php
namespace Buybrain\Nervus\Adapter\Message;

/**
 * Response message as a response to write requests
 *
 * @see WriteRequest
 */
class WriteResponse extends AbstractResponse
{
    /**
     * @return WriteResponse
     */
    public static function success()
    {
        return self::emptySuccess();
    }
}
