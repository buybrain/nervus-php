<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Adapter\Message\ReadRequest;
use Buybrain\Nervus\Adapter\Message\ReadResponse;
use Buybrain\Nervus\Adapter\Message\SignalAckRequest;
use Buybrain\Nervus\Adapter\Message\SignalAckResponse;
use Buybrain\Nervus\Adapter\Message\SignalRequest;
use Buybrain\Nervus\Adapter\Message\SignalResponse;
use Buybrain\Nervus\Adapter\Message\WriteRequest;
use Buybrain\Nervus\Adapter\Message\WriteResponse;
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
                ['Id' => ['Id' => 234, 'Type' => 'test'], 'Data' => 'Some data', 'Deleted' => false],
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
                [
                    'Status' => true, 
                    'Error' => null, 
                    'Entities' => [
                        ['Id' => ['Type' => 'test', 'Id' => '123'], 'Data' => 'Some data', 'Deleted' => false]
                    ]
                ],
                ReadResponse::class
            ],
            [
                ['Entities' => [['Id' => ['Type' => 'test', 'Id' => '123'], 'Data' => 'Some data', 'Deleted' => false]]],
                WriteRequest::class
            ],
            [
                ['Status' => true, 'Error' => null],
                WriteResponse::class
            ]
        ];
    }
}
