<?php
namespace Buybrain\Nervus\Adapter;

class SignalResponse extends AbstractResponse
{
    /** @var Signal */
    private $signal;

    /**
     * @param Signal $signal
     * @return SignalResponse
     */
    public static function success(Signal $signal)
    {
        $res = self::emptySuccess();
        $res->signal = $signal;
        return $res;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), ['Signal' => $this->signal]);
    }
}
