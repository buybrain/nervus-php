<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Codec\JsonCodec;
use Buybrain\Nervus\EntityId;

class SignalAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSignalAdapter()
    {
        $request = new SignalRequest();
        $signal = [new EntityId('test', 123)];
        $response = new SignalResponse(true);
        $reqHandler = new MockSignalRequestHandler($signal);

        $input = fopen('php://temp', 'r+');
        $output = fopen('php://temp', 'r+');
        fwrite($input, json_encode($request) . "\n");
        fwrite($input, json_encode($response) . "\n");
        rewind($input);

        $SUT = new SignalAdapter(
            new AdapterContext(new JsonCodec(), $input, $output),
            $reqHandler
        );

        $SUT->step();

        rewind($output);
        $written = stream_get_contents($output);
        $expected = json_encode(Signal::success($signal));

        $this->assertEquals($expected, $written);
    }
}