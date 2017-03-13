<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

require __DIR__ . '/../vendor/autoload.php';

const INNER_ITER = 10;

$codecs = [
    new JsonCodec(),
    new PureMessagePackCodec(),
    new NativeMessagePackCodec(),
];

echo 'Testing with a tiny payload', PHP_EOL;
$message = new Entity(new EntityId('test', 1), random_bytes(1));
foreach ($codecs as $codec) {
    benchmark($codec, $message, 5000);
}

echo PHP_EOL, 'Now testing with a big 4MB payload', PHP_EOL;
echo 'Preparing payload', PHP_EOL;
$message = new Entity(new EntityId('test', 1), random_bytes(4 * 1024 * 1024));
foreach ($codecs as $codec) {
    benchmark($codec, $message, 10);
}

function benchmark(Codec $codec, $message, $outerIter)
{
    echo 'Starting ', get_class($codec), PHP_EOL;
    $start = microtime(true);
    for ($o = 0; $o < $outerIter; $o ++) {
        $stream = fopen('php://temp', 'r+');
        $enc = $codec->newEncoder($stream);
        $dec = $codec->newDecoder($stream);

        for ($i = 0; $i < INNER_ITER; $i++) {
            $enc->encode($message);
        }
        rewind($stream);
        for ($i = 0; $i < INNER_ITER; $i++) {
            $dec->decode(Entity::class);
        }
    }
    $end = microtime(true);
    echo 'Duration: ', ($end - $start), ' seconds', PHP_EOL;
}