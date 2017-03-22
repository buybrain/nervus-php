<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Exception;

/**
 * Base class for all read adapters
 */
abstract class ReadAdapter extends Adapter
{
    protected function doStep()
    {
        /** @var ReadRequest $req */
        $req = $this->decoder->decode(ReadRequest::class);
        try {
            $this->checkUnsupportedTypes($req->getIds());
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

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'read';
    }

    /**
     * Start composing a new read adapter
     * 
     * @return ComposableReadAdapter
     */
    public static function compose()
    {
        return new ComposableReadAdapter();
    }
}
