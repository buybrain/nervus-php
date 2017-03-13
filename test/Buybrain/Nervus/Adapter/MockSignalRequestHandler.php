<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

class MockSignalRequestHandler implements SignalRequestHandler
{
    /** @var EntityId[] */
    private $signal;
    /** @var SignalResponse */
    private $response;

    /**
     * @param EntityId[] $signal
     */
    public function __construct(array $signal)
    {
        $this->signal = $signal;
    }


    public function onRequest(SignalCallback $callback)
    {
        $this->response = $callback->onSignal($this->signal);
    }

    /**
     * @return SignalResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}