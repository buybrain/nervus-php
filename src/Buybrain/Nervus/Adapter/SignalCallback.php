<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

class SignalCallback
{
    /** @var callable */
    private $onSuccess;

    public function __construct(callable $onSuccess)
    {
        $this->onSuccess = $onSuccess;
    }

    /**
     * @param EntityId[] $ids
     * @param callable $onAck
     * @return SignalAckRequest
     */
    public function onSuccess(array $ids, callable $onAck)
    {
        return call_user_func($this->onSuccess, $ids, $onAck);
    }
}
