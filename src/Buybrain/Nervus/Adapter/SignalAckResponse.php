<?php
namespace Buybrain\Nervus\Adapter;

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
