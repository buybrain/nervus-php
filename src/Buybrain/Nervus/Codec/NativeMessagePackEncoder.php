<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Objects;

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