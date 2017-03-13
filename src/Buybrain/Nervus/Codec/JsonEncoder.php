<?php
namespace Buybrain\Nervus\Codec;

use InvalidArgumentException;
use JsonSerializable;

class JsonEncoder extends AbstractEncoder
{
    /**
     * @param $data
     * @return string
     */
    protected function serialize($data)
    {
        if (is_array($data)) {
            array_walk($data, [JsonEncoder::class, 'validate']);
        } else {
            self::validate($data);
        }
        return json_encode($data) . "\n";
    }

    private static function validate($data)
    {
        if (is_object($data) && !$data instanceof JsonSerializable) {
            throw new InvalidArgumentException('Data for encoding should implement ' . JsonSerializable::class);
        }
    }
}
