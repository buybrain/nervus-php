<?php
namespace Example\Write;

use Buybrain\Nervus\Adapter\WriteAdapter;
use Buybrain\Nervus\Entity;

require __DIR__ . '/../vendor/autoload.php';

/*
    Example implementation of a write adapter using the PHP adapter library.
    When asked to write entities, it will just wait for a bit
 */

class MyWriteAdapter extends WriteAdapter
{
    /**
     * @param Entity[] $entities
     */
    public function onRequest(array $entities)
    {
        sleep(1);
    }
}

(new MyWriteAdapter())->socketAddr(getopt('', ['socket:'])['socket'])->run();
