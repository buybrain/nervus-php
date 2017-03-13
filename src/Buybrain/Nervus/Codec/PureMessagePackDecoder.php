<?php
namespace Buybrain\Nervus\Codec;

use MessagePack\BufferUnpacker;
use RuntimeException;

class PureMessagePackDecoder extends AbstractDecoder
{
    const BUFFER_SIZE = 1048576; // 1 MB

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
            $data = fread($this->stream, self::BUFFER_SIZE);
            if ($data === false) {
                if (feof($this->stream)) {
                    throw new RuntimeException('Encountered EOF while decoding');
                }
                throw new RuntimeException('Error while reading from stream: ');
            }

            $this->unpacker->append($data);
            $this->buffer = $this->unpacker->tryUnpack();
        }
        return array_shift($this->buffer);
    }
}