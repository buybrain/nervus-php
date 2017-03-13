<?php
namespace Buybrain\Nervus\Codec;

interface Decoder
{
    /**
     * Decode the next value, optionally into a given class
     * 
     * @param string|null $class optional class name to decode into
     * @return mixed instance of the given class, or raw struct or primitive when no class is supplied
     */
    public function decode($class = null);

    /**
     * Decode into a list of objects of the given class
     * 
     * @param string $class the class name to decode into
     * @return array of instances of the given class
     */
    public function decodeList($class);
}
