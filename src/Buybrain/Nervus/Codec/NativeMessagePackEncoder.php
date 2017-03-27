<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Objects;

/**
 * Decoder that writes MessagePack encoded messages using a native PECL extension
 */
class NativeMessagePackEncoder extends AbstractEncoder
{
    /**
     * @param $data
     * @return string
     */
    protected function serialize($data)
    {
        return msgpack_pack(Objects::toPrimitiveOrStruct($data));
    }
}
