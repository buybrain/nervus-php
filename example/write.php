<?php
namespace Example\Write;

use Buybrain\Nervus\Adapter\WriteAdapter;

require __DIR__ . '/../vendor/autoload.php';

/*
    Example implementation of a write adapter using the PHP adapter library.
    When asked to write entities, it will just wait for a bit
 */

WriteAdapter::compose()
    ->type('example', function (array $entities) {
        // Write entities here
        sleep(1);
    })
    ->socketAddr(getopt('', ['socket:'])['socket'])
    ->run();
