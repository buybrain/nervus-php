<?php
namespace Buybrain\Nervus\Adapter\Message;

use Exception;
use JsonSerializable;

/**
 * Base class for response messages that get sent back to the nervus host
 */
abstract class AbstractResponse implements JsonSerializable
{
    /** @var bool */
    private $status;
    /** @var string|null */
    private $error;

    /**
     * @param bool $status
     * @param string|null
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
     * Create a new error response message
     *
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
