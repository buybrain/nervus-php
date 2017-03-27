<?php
namespace Buybrain\Nervus\Adapter\Message;

use JsonSerializable;

class SignalRequest implements JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
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
