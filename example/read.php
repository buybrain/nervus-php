<?php
namespace Example\Read;

use Buybrain\Nervus\Adapter\ReadAdapter;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

/*
    Example implementation of a read adapter using the PHP adapter library.
    When asked to read entities, it will just give back static data.
 */

require __DIR__ . '/../vendor/autoload.php';

class ExampleReadAdapter extends ReadAdapter
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

    /**
     * @return string[]
     */
    public function getSupportedEntityTypes()
    {
        return ['example'];
    }
}

(new ExampleReadAdapter())->socketAddr(getopt('', ['socket:'])['socket'])->run();
