<?php
namespace Buybrain\Nervus\Adapter\Config;

use JsonSerializable;

/**
 * AdapterConfig encodes the capabilities of a nervus adapter. It is sent to the nervus host first thing when an adapter
 * starts, so that the host knows what the adapter can do and in what encoding to communicate.
 */
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
     * @param string $codec the name of the codec to use, as given by Codec::getName()
     * @param string $adapterType the type of the adapter e.g. read, write or signal
     * @param string[]|null $entityTypes optional list of supported entity types
     * @param ExtraAdapterConfig $extra specialized configuration for certain adapter types
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
