<?php
namespace Buybrain\Nervus\Adapter\Config;

use JsonSerializable;

class AdapterConfig implements JsonSerializable
{
    /** @var string */
    private $codec;
    /** @var string */
    private $adapterType;
    /** @var string[]|null */
    private $entityTypes;
    /** @var ExtraAdapterConfig */
    private $extra;

    /**
     * @param string $codec
     * @param string $adapterType
     * @param string[]|null $entityTypes
     * @param ExtraAdapterConfig $extra
     */
    public function __construct($codec, $adapterType, array $entityTypes = null, ExtraAdapterConfig $extra = null)
    {
        $this->codec = $codec;
        $this->adapterType = $adapterType;
        $this->entityTypes = $entityTypes;
        $this->extra = $extra;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'Codec' => $this->codec,
            'AdapterType' => $this->adapterType,
            'EntityTypes' => $this->entityTypes,
            'Extra' => $this->extra
        ];
    }
}
