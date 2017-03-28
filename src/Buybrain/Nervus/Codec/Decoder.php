<?php
namespace Buybrain\Nervus\Codec;

/**
 * Interface for decoders
 */
interface Decoder
{
    /**
     * Decode the next value, optionally into a given class
     *
     * @param string|null $class optional class name to decode into
     * @return mixed instance of the given class, or raw struct or primitive when no class is supplied
     */
    public function decode($class = null);
}
