<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

class MockSignalAdapter extends SignalAdapter
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
        parent::__construct();
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
