<?php
namespace Buybrain\Nervus\Adapter\Config;

/**
 * Specialized adapter configuration for signal adapters
 *
 * @see AdapterConfig
 */
class SignalAdapterConfig implements ExtraAdapterConfig
{
    /** @var float */
    private $interval;

    /**
     * @param float $interval
     */
    public function __construct($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'interval' => $this->interval,
        ];
    }
}
