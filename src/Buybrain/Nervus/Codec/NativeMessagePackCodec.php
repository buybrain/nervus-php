<?php
namespace Buybrain\Nervus\Codec;

use RuntimeException;

/**
 * Codec that reads and writes MessagePack encoded messages using a native PECL extension
 */
class NativeMessagePackCodec extends AbstractCodec
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
        return new NativeMessagePackDecoder($stream, $this->mapper);
    }

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream)
    {
        self::assertSupported();
        return new NativeMessagePackEncoder($stream, $this->mapper);
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
