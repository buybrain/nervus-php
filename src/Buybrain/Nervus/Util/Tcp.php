<?php
namespace Buybrain\Nervus\Util;

use Buybrain\Nervus\Exception\Exception;

/**
 * Utility class for easy creation of TCP connections
 */
class Tcp
{
    /**
     * Connect to a TCP address and return a stream resource
     *
     * @param $addr string
     * @return resource
     */
    public static function dial($addr)
    {
        $socket = stream_socket_client('tcp://' . $addr);
        if ($socket === false) {
            throw new Exception('Could not dial to ' . $addr . ': ' . socket_strerror(socket_last_error()));
        }
        return $socket;
    }

}
