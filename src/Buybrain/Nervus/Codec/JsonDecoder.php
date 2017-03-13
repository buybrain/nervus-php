<?php
namespace Buybrain\Nervus\Codec;

use RuntimeException;

class JsonDecoder extends AbstractDecoder
{
    const BUFFER_SIZE = 536870912; // 512 MB

    /**
     * @return array
     */
    protected function decodeStruct()
    {
        // JSON messages are encoded as one message per line. Read a whole line from the stream.
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
}
