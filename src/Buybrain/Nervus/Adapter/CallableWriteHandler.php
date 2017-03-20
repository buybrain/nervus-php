<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;

class CallableWriteHandler implements WriteHandler
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
     * @param Entity[] $entities
     */
    function write(array $entities)
    {
        call_user_func($this->callable, $entities);
    }
}
