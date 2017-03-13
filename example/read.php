<?php
namespace Example\Read;

use Buybrain\Nervus\Adapter\ReadAdapter;
use Buybrain\Nervus\Adapter\ReadRequestHandler;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/*
	Example implementation of a read adapter using the PHP adapter library.
	When asked to read entities, it will just give back static data.
 */

require __DIR__ . '/../vendor/autoload.php';

ReadAdapter::newDefault(new Handler())->run();

class Handler implements ReadRequestHandler
{
    /**
     * @param EntityId[] $ids
     * @return Entity[]
     */
    public function onRequest(array $ids)
    {
        // For every ID, just return an entity with 'example' as data
        $res = [];
        foreach ($ids as $id) {
            $res[] = new Entity($id, 'example');
        }
        return $res;
    }
}