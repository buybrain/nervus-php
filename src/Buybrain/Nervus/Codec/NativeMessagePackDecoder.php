<?php
namespace Buybrain\Nervus\Codec;

use MessagePackUnpacker;
use RuntimeException;

class NativeMessagePackDecoder extends AbstractDecoder
{
    const BUFFER_SIZE = 1048576; // 1 MB

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
            $data = fread($this->stream, self::BUFFER_SIZE);
            if ($data === false) {
                if (feof($this->stream)) {
                    throw new RuntimeException('Encountered EOF while decoding');
                }
                throw new RuntimeException('Error while reading from stream');
            }
            $this->unpacker->feed($data);
        }
        return $this->unpacker->data();
    }
}