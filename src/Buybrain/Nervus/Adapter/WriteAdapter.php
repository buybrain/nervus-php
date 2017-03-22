<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Exception;

/**
 * Base class for all write adapters
 */
abstract class WriteAdapter extends Adapter
{
    protected function doStep()
    {
        /** @var WriteRequest $req */
        $req = $this->decoder->decode(WriteRequest::class);
        try {
            $this->checkUnsupportedTypes($req->getEntities());
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

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'write';
    }

    /**
     * Start composing a new write adapter
     * 
     * @return ComposableWriteAdapter
     */
    public static function compose()
    {
        return new ComposableWriteAdapter();
    }
}
