<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;

interface WriteHandler
{
    /**
     * @param Entity[] $entities
     */
    function write(array $entities);
}
