<?php
namespace Buybrain\Nervus\Adapter;

use JsonSerializable;

class AdapterConfig implements JsonSerializable
{
    /** @var string */
    private $codec;
    /** @var string */
    private $adapterType;
    /** @var string[]|null */
    private $entityTypes;

    /**
     * @param string $codec
     * @param string $adapterType
     * @param string[]|null $entityTypes
     */
    public function __construct($codec, $adapterType, array $entityTypes = null)
    {
        $this->codec = $codec;
        $this->adapterType = $adapterType;
        $this->entityTypes = $entityTypes;
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
        ];
    }
}
