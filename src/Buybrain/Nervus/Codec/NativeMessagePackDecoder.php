<?php
namespace Buybrain\Nervus\Codec;

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
     */
    public function __construct($stream)
    {
        parent::__construct($stream);
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
