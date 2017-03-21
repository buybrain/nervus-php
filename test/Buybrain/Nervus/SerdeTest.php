<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Adapter\ReadRequest;
use Buybrain\Nervus\Adapter\ReadResponse;
use Buybrain\Nervus\Adapter\SignalAckRequest;
use Buybrain\Nervus\Adapter\SignalAckResponse;
use Buybrain\Nervus\Adapter\SignalRequest;
use Buybrain\Nervus\Adapter\SignalResponse;
use Buybrain\Nervus\Adapter\WriteRequest;
use Buybrain\Nervus\Adapter\WriteResponse;
use Buybrain\Nervus\Util\Objects;
use JsonSerializable;
use PHPUnit_Framework_TestCase;

class SerdeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $input
     * @param string $classname
     */
    public function testRoundtrip(array $input, $classname)
    {
        $decoded = $classname::fromArray($input);
        $this->assertTrue($decoded instanceof $classname);
        $this->assertTrue($decoded instanceof JsonSerializable);
        $encoded = Objects::toPrimitiveOrStruct($decoded);
        $this->assertEquals($input, $encoded);
    }

    public function dataProvider()
    {
        return [
            [
                ['Id' => 123, 'Type' => 'test'],
                EntityId::class
            ],
            [
                ['Id' => ['Id' => 234, 'Type' => 'test'], 'Data' => 'Some data'],
                Entity::class
            ],
            [
                [],
                SignalRequest::class
            ],
            [
                ['Status' => true, 'Error' => null, 'Signal' => ['Ids' => [['Type' => 'test', 'Id' => '123']]]],
                SignalResponse::class
            ],
            [
                ['Ack' => true],
                SignalAckRequest::class
            ],
            [
                ['Status' => true, 'Error' => null],
                SignalAckResponse::class
            ],
            [
                ['Ids' => [['Type' => 'test', 'Id' => '123']]],
                ReadRequest::class
            ],
            [
                ['Status' => true, 'Error' => null, 'Entities' => [['Id' => ['Type' => 'test', 'Id' => '123'], 'Data' => 'Some data']]],
                ReadResponse::class
            ],
            [
                ['Entities' => [['Id' => ['Type' => 'test', 'Id' => '123'], 'Data' => 'Some data']]],
                WriteRequest::class
            ],
            [
                ['Status' => true, 'Error' => null],
                WriteResponse::class
            ]
        ];
    }
}
