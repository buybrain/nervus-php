<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Adapter\Config\SignalAdapterConfig;
use Buybrain\Nervus\Adapter\Handler\Signaler;
use Buybrain\Nervus\Adapter\Message\Signal;
use Buybrain\Nervus\Adapter\Message\SignalAckRequest;
use Buybrain\Nervus\Adapter\Message\SignalAckResponse;
use Buybrain\Nervus\Adapter\Message\SignalRequest;
use Buybrain\Nervus\Adapter\Message\SignalResponse;
use Buybrain\Nervus\EntityId;
use Exception;

/**
 * Adapter implementation for handling signal dispatch requests. It will use the supplied Signaler for dispatching the
 * actual signals.
 *
 * @see SignalRequest
 * @see SignalAckRequest
 * @see Signaler
 */
class SignalAdapter extends Adapter implements SignalCallback
{
    /** @var Signaler */
    private $signaler;
    /** @var float */
    private $interval = 0;

    /**
     * @param Signaler $signaler handler for actually dispatching new signals
     */
    public function __construct(Signaler $signaler)
    {
        parent::__construct();
        $this->signaler = $signaler;
    }

    protected function doStep()
    {
        // Wait for the next signal request. The request itself doesn't contain any data.
        $this->decoder->decode(SignalRequest::class);

        try {
            $this->signaler->signal($this);
        } catch (Exception $ex) {
            $this->encoder->encode(SignalResponse::error($ex));
        }
    }

    /**
     * Callback method meant to be called by signal handlers when a new signal is available
     * 
     * @param EntityId[] $ids
     * @param callable $onAck will be passed a single boolean $ack argument
     */
    public function onSignal(array $ids, callable $onAck)
    {
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

    /**
     * @return ExtraAdapterConfig|null
     */
    protected function getExtraConfig()
    {
        return new SignalAdapterConfig($this->interval);
    }

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'signal';
    }
}
