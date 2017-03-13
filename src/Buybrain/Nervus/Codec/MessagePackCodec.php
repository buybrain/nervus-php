<?php
namespace Buybrain\Nervus\Codec;

class MessagePackCodec implements Codec
{
    /** @var bool */
    private static $nativeSupported;

    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        return self::nativeSupported() ? new NativeMessagePackDecoder($stream) : new PureMessagePackDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        return self::nativeSupported() ? new NativeMessagePackEncoder($stream) : new PureMessagePackEncoder($stream);
    }

    /**
     * @return bool
     */
    private static function nativeSupported()
    {
        if (self::$nativeSupported === null) {
            self::$nativeSupported = extension_loaded('msgpack');
        }
        return self::$nativeSupported;
    }
}