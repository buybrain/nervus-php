<?php
namespace Buybrain\Nervus\Adapter;

interface Decoder
{
    /**
     * Decode into an object of the given class
     * 
     * @param string $class the class name to decode into
     * @return mixed instance of the given class
     */
    public function decode($class);

    /**
     * Decode into a list of objects of the given class
     * 
     * @param string $class the class name to decode into
     * @return array of instances of the given class
     */
    public function decodeList($class);
}
