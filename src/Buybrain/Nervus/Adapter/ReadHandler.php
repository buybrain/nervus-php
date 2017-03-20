<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

interface ReadHandler
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    function read(array $ids);
}
