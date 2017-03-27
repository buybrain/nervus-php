<?php
namespace Buybrain\Nervus\Codec;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Encoder that writes JSON encoded messages
 */
class JsonEncoder extends AbstractEncoder
{
    /** @var bool */
    private $useNewlines = true;

    /**
     * @param bool $useNewlines whether to insert newlines between consecutive messages
     * @return $this
     */
    public function useNewlines($useNewlines)
    {
        $this->useNewlines = $useNewlines;
        return $this;
    }

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
        return json_encode($data) . ($this->useNewlines ? "\n" : '');
    }

    private static function validate($data)
    {
        if (is_object($data) && !$data instanceof JsonSerializable) {
            throw new InvalidArgumentException('Data for encoding should implement ' . JsonSerializable::class);
        }
    }
}
