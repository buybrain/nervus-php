<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Exception\Exception;

class ComposableReadAdapter extends ReadAdapter
{
    /** @var ReadHandler[] */
    private $handlers = [];

    /**
     * @param string $type
     * @param ReadHandler|callable $handler
     * @return $this
     */
    public function type($type, $handler)
    {
        if (is_callable($handler)) {
            $handler = new CallableReadHandler($handler);
        }
        if (!$handler instanceof ReadHandler) {
            throw new Exception('Handler must be callable or instanceof ReadHandler, got ' . gettype($handler));
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
        // Collect the ids per type
        $perType = [];
        foreach ($ids as $id) {
            $type = $id->getType();
            if (!isset($perType[$type])) {
                $perType[$type] = [];
            }
            $perType[$type][] = $id;
        }

        $this->checkUnsupportedTypes(array_keys($perType));

        // Use the read handlers for every type and combine the results
        $result = [];
        foreach ($perType as $type => $ids) {
            $result[] = $this->handlers[$type]->read($ids);
        }
        return call_user_func_array('array_merge', $result);
    }
}
