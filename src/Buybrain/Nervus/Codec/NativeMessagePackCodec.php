<?php
namespace Buybrain\Nervus\Codec;

class NativeMessagePackCodec implements Codec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return new NativeMessagePackDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return new NativeMessagePackEncoder($stream);
    }
}