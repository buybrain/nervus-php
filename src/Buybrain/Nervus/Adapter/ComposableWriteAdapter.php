<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\Exception\Exception;

class ComposableWriteAdapter extends WriteAdapter
{
    /** @var WriteHandler[] */
    private $handlers = [];

    /**
     * @param string $type
     * @param WriteHandler|callable $handler
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
        $perType = [];
        foreach ($entities as $entity) {
            $type = $entity->getId()->getType();
            if (!isset($perType[$type])) {
                $perType[$type] = [];
            }
            $perType[$type][] = $entity;
        }

        $this->checkUnsupportedTypes(array_keys($perType));

        foreach ($perType as $type => $entities) {
            $this->handlers[$type]->write($entities);
        }
    }
}
