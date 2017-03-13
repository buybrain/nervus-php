<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Streams;
use RuntimeException;

class JsonDecoder implements Decoder
{
    const BUFFER_SIZE = 536870912; // 512 MB

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

    private function decodeStruct()
    {
        // JSON messages are encoded as one message per line. Read a whole line from the stream.
        error_reporting(E_ALL|E_STRICT);
        ini_set("display_startup_errors", 1);
       
        $line = stream_get_line($this->stream, self::BUFFER_SIZE, "\n");
        if ($line === false) {
            if (feof($this->stream)) {
                throw new RuntimeException('Encountered EOF while decoding');
            }
            throw new RuntimeException('Error while reading from stream: ');
        }
        $data = json_decode($line, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf(
                "Error while decoding JSON: %s\n\nData was:\n\n%s",
                json_last_error_msg(),
                $line
            ));
        }

        return $data;
    }

    /**
     * @param string $class the class name to decode into
     * @return mixed instance of the given class
     */
    public function decode($class)
    {
        self::validateClass($class);
        $data = $this->decodeStruct();
        if (!is_array($data)) {
            throw new RuntimeException('Error while decoding JSON, expected array, got ' . json_encode($data));
        }
        return call_user_func([$class, 'fromArray'], $this->decodeStruct());
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
                    "Error while decoding JSON array element, expected array, got %s. \n\nWhole payload was:\n\n%s",
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
}
