<?php
namespace Buybrain\Nervus\Codec;

use RuntimeException;

class NativeMessagePackCodec implements Codec
{
    /** @var bool */
    private static $supported;

    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream)
    {
        self::assertSupported();
        return new NativeMessagePackDecoder($stream);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        self::assertSupported();
        return new NativeMessagePackEncoder($stream);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'msgpack';
    }

    /**
     * @return bool
     */
    public static function isSupported()
    {
        if (self::$supported === null) {
            self::$supported = extension_loaded('msgpack');
        }
        return self::$supported;
    }

    private static function assertSupported()
    {
        if (!self::isSupported()) {
            throw new RuntimeException(
                'Native MessagePack codec is not supported. ' .
                'Please follow the instructions at https://github.com/msgpack/msgpack-php to install it.'
            );
        }
    }
}
