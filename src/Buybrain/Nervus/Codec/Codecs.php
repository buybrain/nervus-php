<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Codec\Mapper\EntityJsonMapperStrategy;
use Buybrain\Nervus\Codec\Mapper\BasicStructMapperStrategy;
use Buybrain\Nervus\Codec\Mapper\StructMapper;

/**
 * Collection of static factory methods for creating codecs that are ready for use in the nervus system
 */
class Codecs
{
    /**
     * Create a JSON codec. This codec will be configured to deal with base64 encoding of binary data in Entities.
     * 
     * @return JsonCodec
     */
    public static function json()
    {
        return new JsonCodec(new StructMapper([
            new EntityJsonMapperStrategy(),
            new BasicStructMapperStrategy(),
        ]));
    }

    /**
     * @return Codec
     */
    public static function messagePack()
    {
        return new MessagePackCodec(self::defaultMapper());
    }

    /**
     * @return Codec
     */
    public static function nativeMessagePack()
    {
        return new NativeMessagePackCodec(self::defaultMapper());
    }

    /**
     * @return Codec
     */
    public static function pureMessagePack()
    {
        return new PureMessagePackCodec(self::defaultMapper());
    }

    /**
     * @return StructMapper
     */
    private static function defaultMapper()
    {
        return new StructMapper([
            new BasicStructMapperStrategy(),
        ]);
    }
}
