<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Message\Signal;
use Buybrain\Nervus\Adapter\Message\SignalAckRequest;
use Buybrain\Nervus\Adapter\Message\SignalAckResponse;
use Buybrain\Nervus\Adapter\Message\SignalResponse;
use Buybrain\Nervus\Codec\Decoder;
use Buybrain\Nervus\Codec\Encoder;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Exception\Exception;

/**
 * The signal callback is passed to the handler function of a signal adapter. It is best viewed as an extension of the
 * signal adapter itself.
 *
 * @see SignalAdapter
 */
class SignalCallback
{
    /** @var Encoder */
    private $encoder;
    /** @var Decoder */
    private $decoder;

    public function __construct(Encoder $encoder, Decoder $decoder)
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    /**
     * @param EntityId[] $ids
     * @param callable $onAck
     */
    public function onSuccess(array $ids, callable $onAck)
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
}
