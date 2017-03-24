<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Util\Arrays;
use Buybrain\Nervus\Util\TypedUtils;
use Exception;

class ReadAdapter extends Adapter
{
    /** @var Reader[] */
    private $readers = [];
    /** @var string[] */
    private $supportedTypes = [];

    protected function doStep()
    {
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
        // We will assign every entity type to the first reader that supports it
        $perType = TypedUtils::groupByType($ids);
        foreach ($this->readers as $reader) {
            $remainingTypes = array_keys($perType);
            $readerTypes = $reader->getSupportedEntityTypes();
            $useTypes = $readerTypes === null ? $remainingTypes : array_intersect($readerTypes, $remainingTypes);
            if (count($useTypes) > 0) {
                $adapterIds = [];
                foreach ($useTypes as $type) {
                    $adapterIds[] = $perType[$type];
                    unset($perType[$type]);
                }
                $result[] = $reader->read(Arrays::flatten($adapterIds));
                if (count($perType) === 0) {
                    // Done
                    break;
                }
            }
        }
        return Arrays::flatten($result);
    }

    /**
     * @param Reader $reader
     * @return $this
     */
    public function add(Reader $reader)
    {
        if ($reader->getSupportedEntityTypes() === null) {
            // The new reader supports all types, so the adapter also supports all types
            $this->supportedTypes = null;
        } else if ($this->supportedTypes !== null) {
            // The reader support specific types, and so does the adapter, so add the new types from the reader
            $this->supportedTypes = array_unique(array_merge(
                $this->supportedTypes,
                $reader->getSupportedEntityTypes()
            ));
        }
        $this->readers[] = $reader;
        return $this;
    }

    /**
     * @return string
     */
    protected function getAdapterType()
    {
        return 'read';
    }

    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes()
    {
        return $this->supportedTypes;
    }
}
