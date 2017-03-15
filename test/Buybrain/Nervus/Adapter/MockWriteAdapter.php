<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;

class MockWriteAdapter extends WriteAdapter
{
    /**
     * @param Entity[] $entities
     */
    public function onRequest(array $entities)
    {
        // Do nothing, pretend write succeeded
    }
}
