<?php
namespace Buybrain\Nervus\Codec;

/**
 * Codec that reads and writes JSON encoded messages
 */
class JsonCodec implements Codec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return new JsonDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return new JsonEncoder($stream);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'json';
    }
}
