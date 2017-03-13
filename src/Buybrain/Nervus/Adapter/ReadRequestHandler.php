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
     * @throws Exception
     */
    public function onRequest(array $ids);
}
