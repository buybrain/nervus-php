<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\Util\TypedUtils;

/**
 * Implementation of ReadAdapter that can be composed of multiple handlers specialized for particular entity types
 */
class ComposableReadAdapter extends ReadAdapter
{
    /** @var ReadHandler[] */
    private $handlers = [];

    /**
     * Register a handler for a specific entity type
     * 
     * @param string $type
     * @param ReadHandler|callable $handler will be called for read requests for the given entity type
     * @return $this
     */
    public function type($type, $handler)
    {
        if (is_callable($handler)) {
            $handler = new CallableReadHandler($handler);
        }
        if (!$handler instanceof ReadHandler) {
            throw new Exception('Handler must be callable or instance of ReadHandler, got ' . gettype($handler));
        }
        $this->handlers[$type] = $handler;
        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes()
    {
        return array_keys($this->handlers);
    }

    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    protected function onRequest(array $ids)
    {
        // Use the read handlers for every type and combine the results
        $result = [];
        foreach (TypedUtils::groupByType($ids) as $type => $ids) {
            $result[] = $this->handlers[$type]->read($ids);
        }
        return call_user_func_array('array_merge', $result);
    }
}
