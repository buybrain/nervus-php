<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Handler\Reader;
use Buybrain\Nervus\Adapter\Message\ReadRequest;
use Buybrain\Nervus\Adapter\Message\ReadResponse;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Util\Arrays;
use Exception;

/**
 * Adapter implementation for handling entity read requests. It will use the registered Readers to obtain the data.
 *
 * @see ReadRequest
 * @see Reader
 */
class ReadAdapter extends TypedAdapter
{
    /**
     * Add a reader to this adapter for handling incoming read requests
     *
     * @param Reader $reader
     * @return $this
     */
    public function add(Reader $reader)
    {
        return $this->addHandler($reader);
    }

    protected function doStep()
    {
        // Wait for the next read request
        /** @var ReadRequest $req */
        $req = $this->decoder->decode(ReadRequest::class);
        try {
            $this->checkUnsupportedTypes($req->getIds());
            $entities = $this->read($req->getIds());
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
    private function read(array $ids)
    {
        $result = [];
        foreach ($this->assignToHandlers($ids) as $tuple) {
            /**
             * @var Reader $handler
             * @var EntityId[] $assigned
             */
            list($handler, $assigned) = $tuple;
            $result[] = $handler->read($assigned);
        }

        return Arrays::flatten($result);
    }

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'read';
    }
}
