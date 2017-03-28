<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use MessagePackUnpacker;

/**
 * Decoder that reads MessagePack encoded messages using a native PECL extension
 */
class NativeMessagePackDecoder extends AbstractDecoder
{
    /** @var MessagePackUnpacker */
    private $unpacker;

    /**
     * @param resource $stream
     * @param StructMapper $mapper
     */
    public function __construct($stream, StructMapper $mapper)
    {
        parent::__construct($stream, $mapper);
        $this->unpacker = new MessagePackUnpacker();
    }

    /**
     * @return array
     */
    protected function decodeStruct()
    {
        while (!$this->unpacker->execute()) {
            $this->unpacker->feed($this->readChunk());
        }
        return $this->unpacker->data();
    }
}
