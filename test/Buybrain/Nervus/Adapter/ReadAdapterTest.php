<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Codec\JsonCodec;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use PHPUnit_Framework_TestCase;

class ReadAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testReadAdapter()
    {
        $entityId = new EntityId('test', '123');
        $request = new ReadRequest([$entityId]);

        $input = fopen('php://temp', 'r+');
        $output = fopen('php://temp', 'r+');
        fwrite($input, json_encode($request) . "\n");
        rewind($input);

        $SUT = (new MockReadAdapter())->in($input)->out($output)->codec(new JsonCodec());

        $SUT->step();

        rewind($output);
        $written = stream_get_contents($output);
        $expected =
            json_encode(new AdapterConfig('json', 'read', ['test'])) .
            json_encode(ReadResponse::success([new Entity($entityId, 'test')]));

        $this->assertEquals($expected, trim($written));
    }
}
