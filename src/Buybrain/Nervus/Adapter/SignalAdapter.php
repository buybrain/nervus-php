<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Adapter\Config\SignalAdapterConfig;
use Exception;

abstract class SignalAdapter extends Adapter
{
    /** @var float */
    private $interval = 0;
    
    protected function doStep()
    {
        // Wait for the next signal request
        $this->decoder->decode(SignalRequest::class);

        $callback = new SignalCallback(function (array $ids, callable $onAck) {
            // Send the signal to the host application
            $this->encoder->encode(SignalResponse::success(new Signal($ids)));

            while (true) {
                // Wait for the result, which should be a request to ack or nack the signal
                /** @var SignalAckRequest $ackRequest */
                $ackRequest = $this->decoder->decode(SignalAckRequest::class);

                // Let the implementation handle the ack request
                try {
                    call_user_func($onAck, $ackRequest->isAck());
                    $this->encoder->encode(SignalAckResponse::success());
                    // The acknowledgement worked, we can stop listening for consecutive acknowledgement requests
                    break;
                } catch (Exception $ex) {
                    // Something went wrong. Send the error back and wait for the next acknowledgement request
                    $this->encoder->encode(SignalAckResponse::error($ex));
                }
            }
        });

        try {
            $this->onRequest($callback);
        } catch (Exception $ex) {
            $this->encoder->encode(SignalResponse::error($ex));
        }
    }

    /**
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
