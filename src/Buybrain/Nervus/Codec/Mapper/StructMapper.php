<?php
namespace Buybrain\Nervus\Codec\Mapper;

/**
 * Utility for converting arbitrary data to simple structured types like arrays and primitives, and back to instances 
 * of specific classes. Uses one or more strategies to perform the mapping, so it can be configured in different ways.
 */
class StructMapper
{
    /** @var StructMapperStrategy[] */
    private $strategies;

    /**
     * @param StructMapperStrategy[] $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function map($data)
    {
        foreach ($this->strategies as $strategy) {
            $data = $strategy->map($data, $this);
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @param null $class
     * @return mixed
     */
    public function unmap($data, $class = null)
    {
        foreach ($this->strategies as $strategy) {
            $data = $strategy->unmap($data, $this, $class);
        }
        return $data;
    }
}
