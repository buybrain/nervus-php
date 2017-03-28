<?php
namespace Buybrain\Nervus\Adapter\Message;

use Buybrain\Nervus\Codec\Mapper\StructMapper;

/**
 * Response message as a response to signal requests, containing the next signal
 *
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
     * @param StructMapper $mapper
     * @return SignalResponse
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        /** @var SignalResponse $res */
        $res = parent::fromArray($data, $mapper);
        $res->signal = $mapper->unmap($data['Signal'], Signal::class);
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
