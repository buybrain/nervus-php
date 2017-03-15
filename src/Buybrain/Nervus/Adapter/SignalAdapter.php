<?php
namespace Buybrain\Nervus\Adapter;

use Exception;

abstract class SignalAdapter extends Adapter
{
    protected function doStep()
    {
        $this->decoder->decode(SignalRequest::class);
        try {
            $this->onRequest(new SignalCallback(function (array $ids) {
                $this->encoder->encode(Signal::success($ids));
                /** @var SignalResponse $res */
                return $this->decoder->decode(SignalResponse::class);
            }));
        } catch (Exception $ex) {
            $this->encoder->encode(Signal::error($ex));
        }
    }

    abstract protected function onRequest(SignalCallback $callback);
}
