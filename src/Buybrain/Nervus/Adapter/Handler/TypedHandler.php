<?php
namespace Buybrain\Nervus\Adapter\Handler;

/**
 * Interface for adapter handlers that can support specific entity types
 */
interface TypedHandler
{
    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes();
}
