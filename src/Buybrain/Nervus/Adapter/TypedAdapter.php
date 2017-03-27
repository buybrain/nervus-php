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
            // The new reader supports all types, so the adapter also supports all types
            $this->supportedTypes = null;
        } else if ($this->supportedTypes !== null) {
            // The reader support specific types, and so does the adapter, so add the new types from the reader
            $this->supportedTypes = array_unique(array_merge(
                $this->supportedTypes,
                $handler->getSupportedEntityTypes()
            ));
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
     * @param Typed[] $objects
     * @return array tuples of [Handler, object[]]
     */
    protected function assignToHandlers(array $objects)
    {
        $result = [];

        // We will assign every entity type to the first handler that supports it
        $perType = TypedUtils::groupByType($objects);
        foreach ($this->handlers as $handler) {
            $remainingTypes = array_keys($perType);
            $writerTypes = $handler->getSupportedEntityTypes();
            $useTypes = $writerTypes === null ? $remainingTypes : array_intersect($writerTypes, $remainingTypes);
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
                    '%s encountered unsupported types %s (supported: %s)',
                    get_class($this),
                    implode(', ', $unsupportedTypes),
                    count($this->supportedTypes) > 0 ? implode(', ', $this->supportedTypes) : '*none*'
                ));
            }
        }
    }
}
