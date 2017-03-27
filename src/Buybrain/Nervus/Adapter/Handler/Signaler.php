<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Adapter\SignalCallback;

/**
 * Interface for adapter handlers that can produce entity change signals
 */
interface Signaler
{
    function signal(SignalCallback $callback);
}
