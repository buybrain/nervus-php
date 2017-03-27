<?php
namespace Buybrain\Nervus\Adapter\Message;

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
     * @return ReadResponse
     */
    public static function fromArray(array $data)
    {
        /** @var ReadResponse $res */
        $res = parent::fromArray($data);
        $res->entities = array_map([Entity::class, 'fromArray'], $data['Entities']);
        return $res;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), ['Entities' => $this->entities]);
    }
}
