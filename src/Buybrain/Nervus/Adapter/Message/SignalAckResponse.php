<?php
namespace Buybrain\Nervus\Adapter\Message;

/**
 * Response message as a response to signal ack requests
 * @see SignalAckRequest
 */
class SignalAckResponse extends AbstractResponse
{
    /**
     * @return SignalAckResponse
     */
    public static function success()
    {
        return self::emptySuccess();
    }
}
