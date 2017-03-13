<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Codec\JsonCodec;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;

class WriteAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteAdapter()
    {
        $request = new WriteRequest([new Entity(new EntityId('test', 123), 'test')]);
        $reqHandler = new MockWriteRequestHandler();

        $input = fopen('php://temp', 'r+');
        $output = fopen('php://temp', 'r+');
        fwrite($input, json_encode($request) . "\n");
        rewind($input);

        $SUT = new WriteAdapter(
            new AdapterContext(new JsonCodec(), $input, $output),
            $reqHandler
        );

        $SUT->step();

        rewind($output);
        $written = stream_get_contents($output);
        $expected = json_encode(WriteResponse::success());

        $this->assertEquals($expected, trim($written));
    }
}
