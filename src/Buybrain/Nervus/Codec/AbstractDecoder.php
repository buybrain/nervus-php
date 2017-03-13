<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Streams;
use RuntimeException;

abstract class AbstractDecoder implements Decoder
{
    /** @var resource */
    protected $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        Streams::assertStream($stream);
        $this->stream = $stream;
    }

    /**
     * Decode the next value, optionally into a given class
     *
     * @param string|null $class optional class name to decode into
     * @return mixed instance of the given class, or raw struct or primitive when no class is supplied
     */
    public function decode($class = null)
    {
        $data = $this->decodeStruct();
        if ($class === null) {
            return $data;
        }
        self::validateClass($class);
        if (!is_array($data)) {
            throw new RuntimeException('Error while decoding, expected array, got ' . json_encode($data));
        }
        return call_user_func([$class, 'fromArray'], $data);
    }

    /**
     * Decode into a list of objects of the given class
     *
     * @param string $class the class name to decode into
     * @return array of instances of the given class
     */
    public function decodeList($class)
    {
        self::validateClass($class);
        $data = $this->decodeStruct();
        return array_map(function ($object) use ($class, $data) {
            if (!is_array($object)) {
                throw new RuntimeException(sprintf(
                    "Error while decoding array element, expected array, got %s. \n\nWhole payload was:\n\n%s",
                    json_encode($object),
                    json_encode($data)
                ));
            }
            return call_user_func([$class, 'fromArray'], $object);
        }, $data);
    }

    /**
     * @param string $class
     */
    private static function validateClass($class)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cannot decode to %f, class does not exist', $class));
        }
        if (!method_exists($class, 'fromArray')) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot decode to %s, class does not implement fromArray($data)',
                $class
            ));
        }
    }

    /**
     * @return array
     */
    abstract protected function decodeStruct();
}