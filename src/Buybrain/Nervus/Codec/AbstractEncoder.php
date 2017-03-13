<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Streams;

abstract class AbstractEncoder implements Encoder
{
    /** @var resource */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        Streams::assertStream($stream);
        $this->stream = $stream;
    }

    /**
     * @param mixed $data
     */
    public function encode($data)
    {
        fwrite($this->stream, $this->serialize($data));
    }

    /**
     * @param $data
     * @return string
     */
    abstract protected function serialize($data);
}