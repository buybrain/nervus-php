<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;

interface Writer extends TypedHandler
{
    /**
     * @param Entity[] $entities
     */
    function write(array $entities);
}
