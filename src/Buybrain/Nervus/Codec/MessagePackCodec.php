<?php
namespace Buybrain\Nervus\Codec;

/**
 * Codec that reads and writes MessagePack encoded messages using either a pure PHP implementation or a faster native
 * PECL extension, depending on whether the latter is installed
 */
class MessagePackCodec extends AbstractCodec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return NativeMessagePackCodec::isSupported() ?
            new NativeMessagePackDecoder($stream, $this->mapper) :
            new PureMessagePackDecoder($stream, $this->mapper);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return NativeMessagePackCodec::isSupported() ?
            new NativeMessagePackEncoder($stream, $this->mapper) :
            new PureMessagePackEncoder($stream, $this->mapper);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'msgpack';
    }
}
