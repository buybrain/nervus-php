<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Adapter\Config\TypedAdapterConfig;
use Buybrain\Nervus\Adapter\Handler\TypedHandler;
use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\Util\Arrays;
use Buybrain\Nervus\Util\Typed;
use Buybrain\Nervus\Util\TypedUtils;

/**
 * Base class for typed adapters (read / write). It can register typed handlers and assign typed objects from incoming
 * requests to the appropriate handlers.
 */
abstract class TypedAdapter extends Adapter
{
    /** @var TypedHandler[] */
    private $handlers = [];
    /** @var string[] */
    private $supportedTypes = [];

    /**
     * Add a handler to the adapter. The handler's supported types will be added to the adapter's supported types.
     * 
     * @param TypedHandler $handler
     * @return $this
     */
    protected function addHandler(TypedHandler $handler)
    {
        $this->addHandlerTypes($handler);
        $this->handlers[] = $handler;
        return $this;
    }

    /**
     * @param TypedHandler $handler
     */
    private function addHandlerTypes(TypedHandler $handler)
    {
        if ($handler->getSupportedEntityTypes() === null) {
            // The new handler supports all types, so the adapter also supports all types
            $this->supportedTypes = null;
        } else if ($this->supportedTypes !== null) {
            // The handler support specific types, and so does the adapter, so add the new types from the handler
            $this->supportedTypes = array_unique(array_merge(
                $this->supportedTypes,
                $handler->getSupportedEntityTypes()
            ));
            sort($this->supportedTypes);
        }
    }

    /**
     * @return ExtraAdapterConfig|null
     */
    protected function getExtraConfig()
    {
        return new TypedAdapterConfig($this->supportedTypes);
    }

    /**
     * Assign every entity to the first handler that supports it
     *
     * @param Typed[] $objects
     * @return array tuples of [Handler, object[]]
     */
    protected function assignToHandlers(array $objects)
    {
        $result = [];
        $perType = TypedUtils::groupByType($objects);
        foreach ($this->handlers as $handler) {
            $remainingTypes = array_keys($perType);
            $handlerTypes = $handler->getSupportedEntityTypes();
            $useTypes = $handlerTypes === null ? $remainingTypes : array_intersect($handlerTypes, $remainingTypes);
            if (count($useTypes) > 0) {
                $assigned = [];
                foreach ($useTypes as $type) {
                    $assigned[] = $perType[$type];
                    unset($perType[$type]);
                }
                $result[] = [$handler, Arrays::flatten($assigned)];
                if (count($perType) === 0) {
                    // Done
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Validate that all the entity types from a request are supported by this adapter
     *
     * @param Typed[] $objects
     */
    protected function checkUnsupportedTypes(array $objects)
    {
        if ($this->supportedTypes !== null) {
            $unsupportedTypes = array_diff(TypedUtils::uniqueTypes($objects), $this->supportedTypes);
            if (count($unsupportedTypes) > 0) {
                throw new Exception(sprintf(
                    'Encountered unsupported types %s (supported: %s)',
                    implode(', ', $unsupportedTypes),
                    count($this->supportedTypes) > 0 ? implode(', ', $this->supportedTypes) : '*none*'
                ));
            }
        }
    }
}
