<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Exception\Exception;

/**
 * Decoder that reads JSON encoded messages
 */
class JsonDecoder extends AbstractDecoder
{
    /** @var string */
    private $buffer = '';

    /**
     * @return array
     */
    protected function decodeStruct()
    {
        // JSON messages are encoded as one message per line. Keep reading until we encounter a newline.
        $line = $this->buffer;
        $this->buffer = '';
        while (($end = strpos($line, "\n")) === false) {
            $line .= $this->readChunk();
        }
        // Take the part until the newline, put the part after in an in-memory buffer
        $this->buffer = substr($line, $end + 1);
        $line = substr($line, 0, $end);

        $data = json_decode($line, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(sprintf(
                "Error while decoding JSON: %s\n\nData was:\n\n%s",
                json_last_error_msg(),
                $line
            ));
        }

        return $data;
    }
}
