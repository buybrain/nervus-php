<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

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
        $ids = $this->decoder->decodeList(EntityId::class);
        $entities = $this->requestHandler->onRequest($ids);
        $this->encoder->encode($entities);
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
