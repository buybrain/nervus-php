<?php
namespace Buybrain\Nervus\Adapter\Message;

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
