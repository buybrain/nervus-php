<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Codec\JsonCodec;
use Buybrain\Nervus\EntityId;
use PHPUnit_Framework_TestCase;

class ReadAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testReadAdapter()
    {
        $request = new ReadRequest([new EntityId('test', 123)]);
        $reqHandler = new MockReadRequestHandler();

        $input = fopen('php://temp', 'r+');
        $output = fopen('php://temp', 'r+');
        fwrite($input, json_encode($request) . "\n");
        rewind($input);

        $SUT = new ReadAdapter(
            new AdapterContext(new JsonCodec(), $input, $output),
            $reqHandler
        );

        $SUT->step();

        rewind($output);
        $written = stream_get_contents($output);
        $expected = json_encode(ReadResponse::success($reqHandler->onRequest($request->getIds())));

        $this->assertEquals($expected, $written);
    }
}