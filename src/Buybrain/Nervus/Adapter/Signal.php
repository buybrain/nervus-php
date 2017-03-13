<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;
use Exception;
use JsonSerializable;

class Signal implements JsonSerializable
{
    /** @var bool */
    private $status;
    /** @var string */
    private $error;
    /** @var EntityId[] */
    private $ids;

    /**
     * @param bool $status
     * @param string $error
     * @param EntityId[] $ids
     */
    private function __construct($status, $error, $ids)
    {
        $this->status = $status;
        $this->error = $error;
        $this->ids = $ids;
    }

    /**
     * @param EntityId[] $ids
     * @return Signal
     */
    public static function success(array $ids)
    {
        return new self(true, null, $ids);
    }

    /**
     * @param Exception $error
     * @return Signal
     */
    public static function error(Exception $error)
    {
        return new self(false, $error->getMessage(), null);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'Status' => $this->status,
            'Error' => $this->error,
            'Ids' => $this->ids
        ];
    }
}