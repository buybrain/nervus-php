<?php
namespace Buybrain\Nervus\Codec;

class MessagePackCodec implements Codec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return NativeMessagePackCodec::isSupported() ?
            new NativeMessagePackDecoder($stream) :
            new PureMessagePackDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return NativeMessagePackCodec::isSupported() ?
            new NativeMessagePackEncoder($stream) :
            new PureMessagePackEncoder($stream);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'msgpack';
    }
}
