<?php
namespace Buybrain\Nervus\Codec;

interface Encoder
{
    /**
     * @param mixed $data
     */
    public function encode($data);
}
