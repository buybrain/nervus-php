<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Codec\JsonCodec;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

class WriteAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteAdapter()
    {
        $request = new WriteRequest([new Entity(new EntityId('test', 123), 'test')]);

        $input = fopen('php://temp', 'r+');
        $output = fopen('php://temp', 'r+');
        fwrite($input, json_encode($request) . "\n");
        rewind($input);

        $SUT = (new MockWriteAdapter())->in($input)->out($output)->codec(new JsonCodec());

        $SUT->step();

        rewind($output);
        $written = stream_get_contents($output);
        $expected =
            json_encode(new AdapterConfig('json', 'write', ['test'])) .
            json_encode(WriteResponse::success());

        $this->assertEquals($expected, trim($written));
    }
}
