<?php
namespace Buybrain\Nervus\Codec;

class PureMsgpackCodec implements Codec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return new PureMsgpackDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return new PureMsgpackEncoder($stream);
    }
}