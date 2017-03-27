<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Adapter\SignalCallback;

/**
 * Implementation of Signaler that passes read requests to a callable. Primarily meant for testing and prototyping.
 */
class CallableSignaler implements Signaler
{
    /** @var callable */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function signal(SignalCallback $callback)
    {
        call_user_func($this->callable, $callback);
    }
}
