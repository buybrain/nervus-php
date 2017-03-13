<?php
namespace Example\Write;

use Buybrain\Nervus\Adapter\WriteAdapter;
use Buybrain\Nervus\Adapter\WriteRequestHandler;
use Buybrain\Nervus\Entity;

require __DIR__ . '/../vendor/autoload.php';

/*
	Example implementation of a write adapter using the PHP adapter library.
	When asked to write entities, it will just wait for a bit
 */

WriteAdapter::newDefault(new Handler())->run();

class Handler implements WriteRequestHandler
{
    /**
     * @param Entity[] $entities
     */
    public function onRequest(array $entities)
    {
        sleep(1);
    }
}
