<?php
namespace Buybrain\Nervus\Codec\Mapper;

/**
 * Strategy for use in StructMapper
 *
 * @see StructMapper
 */
interface StructMapperStrategy
{
    /**
     * @param mixed $data
     * @param StructMapper $mapper
     * @return mixed
     */
    public function map($data, StructMapper $mapper);

    /**
     * @param mixed $data
     * @param StructMapper $mapper
     * @param null $class
     * @return mixed
     */
    public function unmap($data, StructMapper $mapper, $class = null);
}
