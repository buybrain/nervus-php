<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;

/**
 * Implementation of Writer that passes write requests to a callable. Primarily meant for testing and prototyping.
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
