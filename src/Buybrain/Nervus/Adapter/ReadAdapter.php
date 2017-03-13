<?php
namespace Buybrain\Nervus\Adapter;

use Exception;

class ReadAdapter extends AbstractAdapter
{
    /** @var ReadRequestHandler */
    private $requestHandler;

    public function __construct(AdapterContext $context, ReadRequestHandler $requestHandler)
    {
        parent::__construct($context);
        $this->requestHandler = $requestHandler;
    }

    protected function doStep()
    {
        /** @var ReadRequest $req */
        $req = $this->decoder->decode(ReadRequest::class);
        try {
            $entities = $this->requestHandler->onRequest($req->getIds());
            $res = ReadResponse::success($entities);
        } catch (Exception $ex) {
            $res = ReadResponse::error($ex);
        }
        $this->encoder->encode($res);
    }

    /**
     * @param ReadRequestHandler $requestHandler
     * @return ReadAdapter
     */
    public static function newDefault(ReadRequestHandler $requestHandler)
    {
        return new self(AdapterContext::newDefault(), $requestHandler);
    }
}
