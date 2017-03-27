<?php
namespace Buybrain\Nervus\Codec;

use MessagePack\BufferUnpacker;

/**
 * Decoder that reads MessagePack encoded messages using a pure PHP implementation
 */
class PureMessagePackDecoder extends AbstractDecoder
{
    /** @var BufferUnpacker */
    private $unpacker;
    /** @var array */
    private $buffer = [];

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        parent::__construct($stream);
        $this->unpacker = new BufferUnpacker();
    }

    /**
     * @return array
     */
    protected function decodeStruct()
    {
        while (count($this->buffer) === 0) {
            $this->unpacker->append($this->readChunk());
            $this->buffer = $this->unpacker->tryUnpack();
        }
        return array_shift($this->buffer);
    }
}
