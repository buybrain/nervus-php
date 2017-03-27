<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

/**
 * Callback meant to be called by signal handlers when a new signal is available
 */
interface SignalCallback
{
    /**
     * @param EntityId[] $ids
     * @param callable $onAck
     */
    public function onSignal(array $ids, callable $onAck);
}
