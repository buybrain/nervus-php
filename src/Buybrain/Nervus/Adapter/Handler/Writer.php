<?php
namespace Buybrain\Nervus\Adapter\Handler;

use Buybrain\Nervus\Entity;

/**
 * Interface for adapter handlers that can write a list of entities
 */
interface Writer extends TypedHandler
{
    /**
     * @param Entity[] $entities
     */
    function write(array $entities);
}
