<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Exception;
use JsonSerializable;

class ReadResponse implements JsonSerializable
{
    /** @var bool */
    private $status;
    /** @var string */
    private $error;
    /** @var Entity[] */
    private $entities;

    /**
     * @param bool $status
     * @param string $error
     * @param Entity[] $entities
     */
    private function __construct($status, $error, array $entities)
    {
        $this->status = $status;
        $this->error = $error;
        $this->entities = $entities;
    }

    /**
     * @param Entity[] $entities
     * @return ReadResponse
     */
    public static function success(array $entities)
    {
        return new self(true, null, $entities);
    }
    
    public static function error(Exception $error)
    {
        return new self(false, $error->getMessage(), null);
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'Status' => $this->status,
            'Error' => $this->error,
            'Entities' => $this->entities
        ];
    }
}
