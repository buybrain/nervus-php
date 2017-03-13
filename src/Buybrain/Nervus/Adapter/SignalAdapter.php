<?php
namespace Buybrain\Nervus\Adapter;

use Exception;

class SignalAdapter extends AbstractAdapter
{
    /** @var SignalRequestHandler */
    private $requestHandler;

    public function __construct(AdapterContext $context, SignalRequestHandler $requestHandler)
    {
        parent::__construct($context);
        $this->requestHandler = $requestHandler;
    }

    protected function doStep()
    {
        $this->decoder->decode(SignalRequest::class);
        try {
            $this->requestHandler->onRequest(new SignalCallback(function (array $ids) {
                $this->encoder->encode(Signal::success($ids));
                /** @var SignalResponse $res */
                return $this->decoder->decode(SignalResponse::class);
            }));
        } catch (Exception $ex) {
            $this->encoder->encode(Signal::error($ex));
        }
    }
}