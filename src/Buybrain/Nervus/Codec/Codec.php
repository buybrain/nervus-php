<?php
namespace Buybrain\Nervus\Codec;

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
