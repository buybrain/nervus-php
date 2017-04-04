<?php
namespace Buybrain\Nervus\Codec\Mapper;

use Buybrain\Nervus\Entity;

/**
 * Mapper strategy for passing entity data through base64 encoding / decoding
 */
class EntityJsonMapperStrategy implements StructMapperStrategy
{
    /**
     * @param mixed StructMapper
     * @param StructMapper $mapper
     * @return mixed
     */
    public function map($data, StructMapper $mapper)
    {
        if ($data instanceof Entity) {
            $data = new Entity($data->getId(), base64_encode($data->getData()), $data->isDeleted());
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @param StructMapper $mapper
     * @param null $class
     * @return mixed
     */
    public function unmap($data, StructMapper $mapper, $class = null)
    {
        if ($class === Entity::class) {
            $data['data'] = base64_decode($data['data']);
        }
        return $data;
    }
}
