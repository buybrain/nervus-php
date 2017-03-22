<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\Util\TypedUtils;

/**
 * Implementation of WriteAdapter that can be composed of multiple handlers specialized for particular entity types
 */
class ComposableWriteAdapter extends WriteAdapter
{
    /** @var WriteHandler[] */
    private $handlers = [];

    /**
     * Register a handler for a specific entity type
     *
     * @param string $type
     * @param WriteHandler|callable $handler will be called for write requests for the given entity type
     * @return $this
     */
    public function type($type, $handler)
    {
        if (is_callable($handler)) {
            $handler = new CallableWriteHandler($handler);
        }
        if (!$handler instanceof WriteHandler) {
            throw new Exception('Handler must be callable or instance of WriteHandler, got ' . gettype($handler));
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
     * @param Entity[] $entities
     */
    protected function onRequest(array $entities)
    {
        foreach (TypedUtils::groupByType($entities) as $type => $entities) {
            $this->handlers[$type]->write($entities);
        }
    }
}
