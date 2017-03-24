<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/**
 * Implementation of ReadHandler that passes read requests to a callable
 */
class CallableReader implements Reader
{
    /** @var callable */
    private $callable;
    /** @var string[]|null */
    private $supportedTypes;

    /**
     * @param callable $callable
     * @param string[]|null $supportedTypes
     */
    public function __construct(callable $callable, array $supportedTypes = null)
    {
        $this->callable = $callable;
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids)
    {
        return call_user_func($this->callable, $ids);
    }

    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes()
    {
        return $this->supportedTypes;
    }
}
