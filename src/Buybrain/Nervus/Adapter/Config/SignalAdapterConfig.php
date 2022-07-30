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
    /** @var int */
    private $priority;

    /**
     * @param float $interval
     * @param int $priority
     */
    public function __construct($interval, $priority)
    {
        $this->interval = (float)$interval;
        $this->priority = (int)$priority;
    }

    function jsonSerialize(): array
    {
        return [
            'interval' => $this->interval,
            'priority' => $this->priority,
        ];
    }
}
