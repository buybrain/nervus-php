<?php
namespace Example\Write;

use Buybrain\Nervus\Adapter\Handler\CallableWriter;
use Buybrain\Nervus\Adapter\WriteAdapter;
use Buybrain\Nervus\Util\Tcp;

require __DIR__ . '/../vendor/autoload.php';

/*
    Example implementation of a write adapter using the PHP adapter library.
    When asked to write entities, it will just wait for a bit
 */

(new WriteAdapter())
    ->add(new CallableWriter(
        function (array $entities) {
            // Write entities here
            sleep(1);
        },
        ['example']
    ))
    ->io(Tcp::dial(getopt('', ['socket:'])['socket']))
    ->run();
