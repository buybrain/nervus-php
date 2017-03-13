<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;

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
        $entities = $this->decoder->decodeList(Entity::class);
        $this->requestHandler->onRequest($entities);
    }
}
