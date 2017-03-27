<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Handler\Writer;
use Buybrain\Nervus\Adapter\Message\WriteRequest;
use Buybrain\Nervus\Adapter\Message\WriteResponse;
use Buybrain\Nervus\Entity;
use Exception;

class WriteAdapter extends TypedAdapter
{
    /**
     * @param Writer $writer
     * @return $this
     */
    public function add(Writer $writer)
    {
        return $this->addHandler($writer);
    }

    protected function doStep()
    {
        /** @var WriteRequest $req */
        $req = $this->decoder->decode(WriteRequest::class);
        try {
            $this->checkUnsupportedTypes($req->getEntities());
            $this->write($req->getEntities());
            $res = WriteResponse::success();
        } catch (Exception $ex) {
            $res = WriteResponse::error($ex);
        }
        $this->encoder->encode($res);
    }

    /**
     * @param Entity[] $entities
     */
    private function write(array $entities)
    {
        foreach ($this->assignToHandlers($entities) as $tuple) {
            /**
             * @var Writer $handler
             * @var Entity[] $assigned
             */
            list($handler, $assigned) = $tuple;
            $handler->write($assigned);
        }
    }

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'write';
    }
}
