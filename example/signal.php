<?php
namespace Example\Signal;

use Buybrain\Nervus\Adapter\SignalAdapter;
use Buybrain\Nervus\Adapter\SignalCallback;
use Buybrain\Nervus\Adapter\SignalRequestHandler;
use Buybrain\Nervus\EntityId;

/*
	Example implementation of a signal adapter using the PHP adapter library.
	When asked for a new signal, it will wait for a little while and emit a few random entity IDs. Then, it will wait
    for acknowledgement.
 */

require __DIR__ . '/../vendor/autoload.php';

class Handler implements SignalRequestHandler
{
    public function onRequest(SignalCallback $callback)
    {
        // Wait for a while
        usleep(500000);
        // Send a fake signal to handler and wait for the result
        $response = $callback->onSignal([new EntityId('example', 123), new EntityId('example', 234)]);
        // Normally, this is where you would check response.Ack to determine whether to retry this, drop it, pause, etc
        if ($response->isAck()) {

        };
    }
}

SignalAdapter::newDefault(new Handler())->run();
