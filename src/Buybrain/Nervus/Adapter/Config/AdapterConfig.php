<?php
namespace Buybrain\Nervus\Adapter\Config;

use JsonSerializable;

/**
 * AdapterConfig encodes the capabilities of a nervus adapter. It is sent to the nervus host first thing when an adapter
 * starts, so that the host knows what the adapter can do and in what encoding to communicate.
 *
 * @see ExtraAdapterConfig
 */
class AdapterConfig implements JsonSerializable
{
    /** @var string */
    private $codec;
    /** @var string */
    private $adapterType;
    /** @var ExtraAdapterConfig|null */
    private $extra;

    /**
     * @param string $codec the name of the codec to use, as given by Codec::getName()
     * @param string $adapterType the type of the adapter e.g. read, write or signal
     * @param ExtraAdapterConfig|null $extra specialized configuration for certain adapter types
     */
    public function __construct($codec, $adapterType, ExtraAdapterConfig $extra = null)
    {
        $this->codec = $codec;
        $this->adapterType = $adapterType;
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
            'Extra' => $this->extra
        ];
    }
}
