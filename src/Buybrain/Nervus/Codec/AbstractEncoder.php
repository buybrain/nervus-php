<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Buybrain\Nervus\Util\Streams;

/**
 * Base class for encoders
 */
abstract class AbstractEncoder implements Encoder
{
    /** @var resource */
    private $stream;
    /** @var StructMapper */
    private $mapper;

    /**
     * @param resource $stream
     * @param StructMapper $mapper
     */
    public function __construct($stream, StructMapper $mapper)
    {
        Streams::assertStream($stream);
        $this->stream = $stream;
        $this->mapper = $mapper;
    }

    /**
     * @param mixed $data
     */
    public function encode($data)
    {
        fwrite($this->stream, $this->serialize($this->mapper->map($data)));
    }

    /**
     * @param $data
     * @return string
     */
    abstract protected function serialize($data);
}
