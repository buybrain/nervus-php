<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;

/**
 * Implementation of Writer that passes write requests to a callable. Primarily meant for testing and prototyping.
 */
class CallableWriter extends AbstractTypedHandler implements Writer
{
    /** @var callable */
    private $callable;

    /**
     * @param callable $callable
     * @param string[]|null $supportedTypes
     */
    public function __construct(callable $callable, array $supportedTypes = null)
    {
        parent::__construct($supportedTypes);
        $this->callable = $callable;
    }
    
    /**
     * @param Entity[] $entities
     */
    function write(array $entities)
    {
        call_user_func($this->callable, $entities);
    }
}
