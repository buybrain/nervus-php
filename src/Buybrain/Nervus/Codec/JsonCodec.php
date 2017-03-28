<?php
namespace Buybrain\Nervus\Codec;

/**
 * Codec that reads and writes JSON encoded messages
 */
class JsonCodec extends AbstractCodec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return new JsonDecoder($stream, $this->mapper);
    }

    /**
     * @param resource $stream
     * @return JsonEncoder
     */
    public function newEncoder($stream)
    {
        return new JsonEncoder($stream, $this->mapper);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'json';
    }
}
