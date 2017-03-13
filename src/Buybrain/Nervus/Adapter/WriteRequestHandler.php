<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;

interface WriteRequestHandler
{
    /**
     * @param Entity[] $entities
     */
    public function onRequest(array $entities);
}
