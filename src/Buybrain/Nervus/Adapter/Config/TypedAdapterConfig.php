<?php
namespace Buybrain\Nervus\Adapter\Config;

class TypedAdapterConfig implements ExtraAdapterConfig
{
    /** @var string[]|null */
    private $entityTypes;

    /**
     * @param string[]|null $entityTypes
     */
    public function __construct($entityTypes)
    {
        $this->entityTypes = $entityTypes;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'EntityTypes' => $this->entityTypes,
        ];
    }
}
