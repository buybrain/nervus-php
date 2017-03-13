<?php
namespace Buybrain\Nervus\Adapter;

use Exception;
use JsonSerializable;

class WriteResponse implements JsonSerializable
{
    /** @var bool */
    private $status;
    /** @var string */
    private $error;

    /**
     * @param bool $status
     * @param string $error
     */
    private function __construct($status, $error)
    {
        $this->status = $status;
        $this->error = $error;
    }

    /**
     * @return WriteResponse
     */
    public static function success()
    {
        return new self(true, null);
    }

    /**
     * @param Exception $error
     * @return WriteResponse
     */
    public static function error(Exception $error)
    {
        return new self(false, $error->getMessage());
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'Status' => $this->status,
            'Error' => $this->error,
        ];
    }
}