<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Adapter\Config\SignalAdapterConfig;
use Exception;

/**
 * Base class for all signal adapters
 */
abstract class SignalAdapter extends Adapter
{
    /** @var float */
    private $interval = 0;

    protected function doStep()
    {
        // Wait for the next signal request. The request itself doesn't contain any data.
        $this->decoder->decode(SignalRequest::class);
        
        try {
            $this->onRequest(new SignalCallback($this->encoder, $this->decoder));
        } catch (Exception $ex) {
            $this->encoder->encode(SignalResponse::error($ex));
        }
    }

    /**
     * Set the interval in seconds that the nervus host should wait between consecutive signal requests
     * 
     * @param float $seconds
     * @return $this
     */
    public function interval($seconds)
    {
        $this->interval = $seconds;
        return $this;
    }

    abstract protected function onRequest(SignalCallback $callback);

    /**
     * @return ExtraAdapterConfig
     */
    protected function getExtraConfig()
    {
        return new SignalAdapterConfig($this->interval);
    }

    /**
     * @return string[]
     */
    public function getSupportedEntityTypes()
    {
        // The concept of supported types isn't really meaningful for signal adapters
        return null;
    }

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'signal';
    }
}
