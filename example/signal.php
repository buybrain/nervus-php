<?php
namespace Example\Signal;

use Buybrain\Nervus\Adapter\SignalAdapter;
use Buybrain\Nervus\Adapter\SignalCallback;
use Buybrain\Nervus\EntityId;

/*
    Example implementation of a signal adapter using the PHP adapter library.
    When asked for a new signal, it will wait for a little while and emit a few random entity IDs. Then, it will wait
    for acknowledgement.
 */

require __DIR__ . '/../vendor/autoload.php';

class MySignalAdapter extends SignalAdapter
{
    public function onRequest(SignalCallback $callback)
    {
        // Wait for a while
        usleep(500000);

        // Send a fake (but successful) signal to handler using $callback->onSuccess and wait for the result
        // Alternatively, if generating a new signal somehow resulted in an error, throw an exception instead
        $ids = [new EntityId('example', "123"), new EntityId('example', "234")];
        $callback->onSuccess($ids, function ($ack) {
            // Normally, this is where you would check $ack to determine whether to retry this, drop it, pause, etc
            // Because handling the acknowledgement could result in an error itself, this callback can throw an
            // exception. It will then be called again at a later time to retry the acknowledgement. If there is no way
            // to recover from failing to handle the acknowledgement by means of retrying, just return without throwing
            // and solve it another way.
            if ($ack) {

            }
        });
    }
}

(new MySignalAdapter())->socketAddr(getopt('', ['socket:'])['socket'])->run();
