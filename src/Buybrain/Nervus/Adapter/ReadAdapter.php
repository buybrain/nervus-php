<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Exception;

abstract class ReadAdapter extends Adapter
{
    protected function doStep()
    {
        /** @var ReadRequest $req */
        $req = $this->decoder->decode(ReadRequest::class);
        try {
            $entities = $this->onRequest($req->getIds());
            $res = ReadResponse::success($entities);
        } catch (Exception $ex) {
            $res = ReadResponse::error($ex);
        }
        $this->encoder->encode($res);
    }

    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    abstract protected function onRequest(array $ids);
}
