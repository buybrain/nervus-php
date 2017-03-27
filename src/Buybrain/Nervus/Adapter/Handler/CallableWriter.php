<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;

/**
 * Implementation of WriteHandler that passes write requests to a callable
 */
class CallableWriter extends AbstractCallableHandler implements Writer
{
    /**
     * @param Entity[] $entities
     */
    function write(array $entities)
    {
        call_user_func($this->callable, $entities);
    }
}
