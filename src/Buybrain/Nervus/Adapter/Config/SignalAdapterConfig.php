<?php
namespace Buybrain\Nervus\Adapter\Config;

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
            'Interval' => $this->interval,
        ];
    }
}
