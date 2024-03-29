<?php
namespace Buybrain\Nervus\Adapter\Config;

/**
 * Specialized adapter configuration for typed adapters (read / write)
 *
 * @see AdapterConfig
 */
class TypedAdapterConfig implements ExtraAdapterConfig
{
    /** @var string[]|null */
    private $entityTypes;

    /**
     * @param string[]|null $entityTypes
     */
    public function __construct(array $entityTypes = null)
    {
        $this->entityTypes = $entityTypes;
    }

    function jsonSerialize(): array
    {
        return [
            'entityTypes' => $this->entityTypes,
        ];
    }
}
