<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Exception;

interface ReadRequestHandler
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    public function onRequest(array $ids);
}
