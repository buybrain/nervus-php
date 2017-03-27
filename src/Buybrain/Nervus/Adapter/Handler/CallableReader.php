<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/**
 * Implementation of Reader that passes read requests to a callable. Primarily meant for testing and prototyping.
 */
class CallableReader extends AbstractTypedHandler implements Reader
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
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids)
    {
        return call_user_func($this->callable, $ids);
    }
}
