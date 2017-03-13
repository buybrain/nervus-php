<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Streams;
use JsonSerializable;

class JsonEncoder implements Encoder
{
    /** @var resource */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        Streams::assertStream($stream);
        $this->stream = $stream;
    }

    /**
     * @param mixed $data
     */
    public function encode($data)
    {
        if (is_array($data)) {
            array_walk($data, [JsonEncoder::class, 'validate']);
        } else {
            self::validate($data);
        }
        fwrite($this->stream, json_encode($data));
    }

    private static function validate($data)
    {
        if (!$data instanceof JsonSerializable) {
            throw new \InvalidArgumentException('Data for encoding should implement ' . JsonSerializable::class);
        }
    }
}
