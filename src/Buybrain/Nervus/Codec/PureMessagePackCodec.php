<?php
namespace Buybrain\Nervus\Codec;

class PureMessagePackCodec implements Codec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return new PureMessagePackDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return new PureMessagePackEncoder($stream);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'msgpack';
    }
}
