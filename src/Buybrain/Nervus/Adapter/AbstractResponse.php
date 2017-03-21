<?php
namespace Buybrain\Nervus\Adapter;

use Exception;
use JsonSerializable;

abstract class AbstractResponse implements JsonSerializable
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
     * @return static
     */
    protected static function emptySuccess()
    {
        return new static(true, null);
    }

    /**
     * @param Exception $error
     * @return static
     */
    public static function error(Exception $error)
    {
        return new static(false, $error->getMessage());
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data['Status'], $data['Error']);
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
