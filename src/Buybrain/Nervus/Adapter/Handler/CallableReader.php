<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/**
 * Implementation of ReadHandler that passes read requests to a callable
 */
class CallableReader extends AbstractCallableHandler implements Reader
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids)
    {
        return call_user_func($this->callable, $ids);
    }
}
