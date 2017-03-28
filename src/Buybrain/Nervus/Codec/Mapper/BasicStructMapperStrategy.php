<?php
namespace Buybrain\Nervus\Codec\Mapper;

use Buybrain\Nervus\Exception\Exception;
use JsonSerializable;

/**
 * Basic mapper strategy for using JsonSerialize / fromArray
 */
class BasicStructMapperStrategy implements StructMapperStrategy
{
    /**
     * @param mixed $data
     * @param StructMapper $mapper
     * @return mixed
     */
    public function map($data, StructMapper $mapper)
    {
        if ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }
        if (is_array($data)) {
            foreach ($data as $i => $value) {
                $data[$i] = $mapper->map($value);
            }
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
        if ($class === null) {
            return $data;
        }
        self::validateClass($class);
        if (!is_array($data)) {
            throw new Exception('Error while unmapping, expected array, got ' . json_encode($data));
        }
        return call_user_func([$class, 'fromArray'], $data, $mapper);
    }

    /**
     * @param string $class
     */
    private static function validateClass($class)
    {
        if (!class_exists($class)) {
            throw new Exception(sprintf('Cannot unmap to %f, class does not exist', $class));
        }
        if (!method_exists($class, 'fromArray')) {
            throw new Exception(sprintf(
                'Cannot unmap to %s, class does not implement fromArray($data)',
                $class
            ));
        }
    }
}
