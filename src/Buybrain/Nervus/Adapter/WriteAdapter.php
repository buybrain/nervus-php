<?php
namespace Buybrain\Nervus\Adapter;

use Exception;

class WriteAdapter extends AbstractAdapter
{
    /** @var WriteRequestHandler */
    private $requestHandler;

    public function __construct(AdapterContext $context, WriteRequestHandler $requestHandler)
    {
        parent::__construct($context);
        $this->requestHandler = $requestHandler;
    }

    protected function doStep()
    {
        /** @var WriteRequest $req */
        $req = $this->decoder->decode(WriteRequest::class);
        try {
            $this->requestHandler->onRequest($req->getEntities());
            $res = WriteResponse::success();
        } catch (Exception $ex) {
            $res = WriteResponse::error($ex);
        }
        $this->encoder->encode($res);
    }

    /**
     * @param WriteRequestHandler $requestHandler
     * @return WriteAdapter
     */
    public static function newDefault(WriteRequestHandler $requestHandler)
    {
        return new self(AdapterContext::newDefault(), $requestHandler);
    }
}
