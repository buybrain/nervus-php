<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

interface ReadRequestHandler
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    public function onRequest(array $ids);
}
