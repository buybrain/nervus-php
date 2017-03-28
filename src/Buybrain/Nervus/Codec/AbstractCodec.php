<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Codec\Mapper\StructMapper;

abstract class AbstractCodec implements Codec
{
    /** @var StructMapper */
    protected $mapper;

    public function __construct(StructMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
