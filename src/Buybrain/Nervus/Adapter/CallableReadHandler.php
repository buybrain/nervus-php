<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/**
 * Implementation of ReadHandler that passes read requests to a callable
 */
class CallableReadHandler implements ReadHandler
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

    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids)
    {
        return call_user_func($this->callable, $ids);
    }
}
