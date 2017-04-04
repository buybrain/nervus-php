<?php
namespace Buybrain\Nervus\Adapter\Message;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use Exception;
use JsonSerializable;

/**
 * Base class for response messages that get sent back to the nervus host. Contains the status (success / failure) and
 * optionally an error message in case of failure.
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
     * @param StructMapper $mapper
     * @return static
     */
    public static function fromArray(array $data, StructMapper $mapper)
    {
        return new static($data['status'], $data['error']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'error' => $this->error,
        ];
    }
}
