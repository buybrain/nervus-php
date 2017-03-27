<?php
namespace Buybrain\Nervus\Adapter\Handler;

/**
 * Base class for adapter handlers that can support specific entity types
 */
abstract class AbstractTypedHandler implements TypedHandler
{
    /** @var string[]|null */
    private $supportedTypes;

    /**
     * @param string[]|null $supportedTypes
     */
    public function __construct(array $supportedTypes = null)
    {
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes()
    {
        return $this->supportedTypes;
    }
}
