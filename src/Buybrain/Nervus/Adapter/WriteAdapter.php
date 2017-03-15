<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Exception;

abstract class WriteAdapter extends Adapter
{
    protected function doStep()
    {
        /** @var WriteRequest $req */
        $req = $this->decoder->decode(WriteRequest::class);
        try {
            $this->onRequest($req->getEntities());
            $res = WriteResponse::success();
        } catch (Exception $ex) {
            $res = WriteResponse::error($ex);
        }
        $this->encoder->encode($res);
    }

    /**
     * @param Entity[] $entities
     */
    abstract protected function onRequest(array $entities);
}
