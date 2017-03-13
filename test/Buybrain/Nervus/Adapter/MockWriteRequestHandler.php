<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;

class MockWriteRequestHandler implements WriteRequestHandler
{
    /**
     * @param Entity[] $entities
     */
    public function onRequest(array $entities)
    {
        // Do nothing, pretend write succeeded
    }
}