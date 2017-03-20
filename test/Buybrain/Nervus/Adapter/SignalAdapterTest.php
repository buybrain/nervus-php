<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Adapter\Config\NoExtraConfig;
use Buybrain\Nervus\Adapter\Config\SignalAdapterConfig;
use Buybrain\Nervus\Codec\JsonCodec;
use Buybrain\Nervus\EntityId;

class SignalAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSignalAdapter()
    {
        $request = new SignalRequest();
        $signal = new Signal([new EntityId('test', 123)]);
        $response = new SignalAckRequest(true);

        $input = fopen('php://temp', 'r+');
        $output = fopen('php://temp', 'r+');
        fwrite($input, json_encode($request) . "\n");
        fwrite($input, json_encode($response) . "\n");
        rewind($input);

        $SUT = (new MockSignalAdapter($signal))->in($input)->out($output)->codec(new JsonCodec())->interval(10);

        $SUT->step();

        rewind($output);
        $written = stream_get_contents($output);
        $expected =
            json_encode(new AdapterConfig('json', 'signal', ['test'], new SignalAdapterConfig(10))) .
            json_encode(SignalResponse::success($signal)) . "\n" . json_encode(SignalAckResponse::success());

        $this->assertEquals($expected, trim($written));
        $this->assertTrue($SUT->getResponse());
    }
}
