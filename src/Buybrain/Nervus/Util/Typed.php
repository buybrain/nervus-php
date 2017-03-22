<?php
namespace Buybrain\Nervus\Util;

/**
 * Interface for anything that has a string based type
 */
interface Typed
{
    /**
     * @return string
     */
    public function getType();
}
