<?php
namespace Buybrain\Nervus\Codec;

/**
 * Interface for encoders
 */
interface Encoder
{
    /**
     * @param mixed $data
     */
    public function encode($data);
}
