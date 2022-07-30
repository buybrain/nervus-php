<?php
namespace Buybrain\Nervus\Adapter\Message;

use JsonSerializable;

/**
 * Request message for acknowledging or rejecting a recently dispatched signal
 */
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
        return new self($data['ack']);
    }

    public function jsonSerialize(): array
    {
        return ['ack' => $this->ack];
    }
}
