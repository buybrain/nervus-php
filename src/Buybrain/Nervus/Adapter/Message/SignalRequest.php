<?php
namespace Buybrain\Nervus\Adapter\Message;

use JsonSerializable;

/**
 * Request message for dispatching the next signal
 */
class SignalRequest implements JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
    }

    /**
     * @param array $data
     * @return SignalRequest
     */
    public static function fromArray(array $data)
    {
        return new self();
    }
}
