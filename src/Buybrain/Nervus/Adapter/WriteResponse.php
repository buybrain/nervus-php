<?php
namespace Buybrain\Nervus\Adapter;

class WriteResponse extends AbstractResponse
{
    /**
     * @return WriteResponse
     */
    public static function success()
    {
        return self::emptySuccess();
    }
}
