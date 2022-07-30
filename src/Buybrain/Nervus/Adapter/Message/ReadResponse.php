<?php
namespace Buybrain\Nervus\Adapter\Message;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Buybrain\Nervus\Entity;

/**
 * Response message as a response to read requests, containing the requested entities
 *
 * @see ReadRequest
 */
class ReadResponse extends AbstractResponse
{
    /** @var Entity[] */
    private $entities;

    /**
     * @param Entity[] $entities
     * @return ReadResponse
     */
    public static function success(array $entities)
    {
        $res = self::emptySuccess();
        $res->entities = $entities;
        return $res;
    }

    /**
     * @param array $data
     * @param StructMapper $mapper
     * @return ReadResponse
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        /** @var ReadResponse $res */
        $res = parent::fromArray($data, $mapper);

        $res->entities = array_map(function ($id) use ($mapper) {
            return $mapper->unmap($id, Entity::class);
        }, $data['entities']);

        return $res;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), ['entities' => $this->entities]);
    }
}
