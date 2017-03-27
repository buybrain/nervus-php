<?php
namespace Buybrain\Nervus\Adapter\Message;

/**
 * Response message as a response to signal requests, containing the next signal
 * @see SignalRequest
 */
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
     * @param array $data
     * @return SignalResponse
     */
    public static function fromArray(array $data)
    {
        /** @var SignalResponse $res */
        $res = parent::fromArray($data);
        $res->signal = Signal::fromArray($data['Signal']);
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
