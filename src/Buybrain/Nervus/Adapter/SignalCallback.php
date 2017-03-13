<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\EntityId;

class SignalCallback
{
    /** @var callable */
    private $onSignal;

    public function __construct(callable $onSignal)
    {
        $this->onSignal = $onSignal;
    }

    /**
     * @param EntityId[] $ids
     */
    public function onSignal(array $ids)
    {
        call_user_func($this->onSignal, $ids);
    }
}