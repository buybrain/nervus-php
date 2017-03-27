<?php
namespace Buybrain\Nervus\Codec;

/**
 * Interface for codecs that can encode data to a stream and decode data from a stream
 */
interface Codec
{
    /**
     * @param resource $stream
     * @return Decoder
     */
    public function newDecoder($stream);

    /**
     * @param resource $stream
     * @return Encoder
     */
    public function newEncoder($stream);

    /**
     * @return string
     */
    public function getName();
}
