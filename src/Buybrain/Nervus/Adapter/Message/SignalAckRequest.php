<?php
namespace Buybrain\Nervus\Adapter\Message;

use JsonSerializable;

class SignalAckRequest implements JsonSerializable
{
    /** @var bool */
    private $ack;

    /**
     * @param bool $ack
     */
    public function __construct($ack)
    {
        $this->ack = $ack;
    }

    /**
     * @return bool
     */
    public function isAck()
    {
        return $this->ack;
    }

    /**
     * @param array $data
     * @return SignalAckRequest
     */
    public static function fromArray(array $data)
    {
        return new self($data['Ack']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return ['Ack' => $this->ack];
    }
}
