<?php
namespace Buybrain\Nervus\Adapter\Handler;

/**
 * Base class for typed adapter handlers that will handle a request by calling a supplied callable
 */
abstract class AbstractCallableHandler extends AbstractTypedHandler
{
    /** @var callable */
    protected $callable;

    /**
     * @param callable $callable
     * @param string[]|null $supportedTypes
     */
    public function __construct(callable $callable, array $supportedTypes = null)
    {
        parent::__construct($supportedTypes);
        $this->callable = $callable;
    }
}
