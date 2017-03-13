<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

class MockSignalRequestHandler implements SignalRequestHandler
{
    /** @var EntityId[] */
    private $signal;

    /**
     * @param EntityId[] $signal
     */
    public function __construct(array $signal)
    {
        $this->signal = $signal;
    }


    public function onRequest(SignalCallback $callback)
    {
        $callback->onSignal($this->signal);
    }
}