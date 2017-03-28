<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\Util\Streams;

/**
 * Base class for decoders. Deals with reading from the stream and decoding raw data structures into instances of
 * specific classes.
 */
abstract class AbstractDecoder implements Decoder
{
    const BUFFER_SIZE = 8192;

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
     * Decode the next value, optionally into a given class
     *
     * @param string|null $class optional class name to decode into
     * @return mixed instance of the given class, or raw struct or primitive when no class is supplied
     */
    public function decode($class = null)
    {
        $data = $this->decodeStruct();
        return $this->mapper->unmap($data, $class);
    }

    /**
     * @return string
     */
    protected function readChunk()
    {
        $data = fread($this->stream, self::BUFFER_SIZE);

        if (($data === '' || $data === false) && feof($this->stream)) {
            throw new Exception('Encountered EOF while decoding');
        }
        if ($data === false) {
            throw new Exception('Error while reading from stream');
        }

        return $data;
    }

    /**
     * @return array
     */
    abstract protected function decodeStruct();
}
