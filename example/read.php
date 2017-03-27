<?php
namespace Example\Read;

use Buybrain\Nervus\Adapter\Handler\CallableReader;
use Buybrain\Nervus\Adapter\ReadAdapter;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\Util\Tcp;

/*
    Example implementation of a read adapter using the PHP adapter library.
    When asked to read entities, it will just give back static data.
 */

require __DIR__ . '/../vendor/autoload.php';

(new ReadAdapter())
    ->add(new CallableReader(
        function (array $ids) {
            // For every ID, just return an entity with 'example' as data
            $res = [];
            foreach ($ids as $id) {
                $res[] = new Entity($id, 'example');
            }
            return $res;
        },
        ['example']
    ))
    ->io(Tcp::dial(getopt('', ['socket:'])['socket']))
    ->run();
