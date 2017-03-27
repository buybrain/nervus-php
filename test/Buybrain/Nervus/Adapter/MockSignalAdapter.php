<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Message\Signal;

class MockSignalAdapter extends SignalAdapter
{
    /** @var Signal */
    private $signal;
    /** @var bool */
    private $response;

    public function __construct(Signal $signal)
    {
        parent::__construct();
        $this->signal = $signal;
    }


    public function onRequest(SignalCallback $callback)
    {
        $callback->onSuccess($this->signal->getIds(), function ($ack) {
            $this->response = $ack;
        });
    }

    /**
     * @return bool
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string[]
     */
    public function getSupportedEntityTypes()
    {
        return ['test'];
    }
}
