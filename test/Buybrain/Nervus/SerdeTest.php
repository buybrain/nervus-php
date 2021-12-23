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
use Buybrain\Nervus\Codec\Mapper\EntityJsonMapperStrategy;
use Buybrain\Nervus\Codec\Mapper\BasicStructMapperStrategy;
use Buybrain\Nervus\Codec\Mapper\StructMapper;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class SerdeTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $input
     * @param string $classname
     */
    public function testRoundtrip(array $input, $classname)
    {
        $mapper = new StructMapper([new BasicStructMapperStrategy()]);
        $decoded = $mapper->unmap($input, $classname);
        $this->assertTrue($decoded instanceof $classname);
        $this->assertTrue($decoded instanceof JsonSerializable);
        $encoded = $mapper->map($decoded);
        $this->assertEquals($input, $encoded);
    }

    public function testEntityJsonMapping()
    {
        $mapper = new StructMapper([new EntityJsonMapperStrategy(), new BasicStructMapperStrategy()]);

        $req = new WriteRequest([new Entity(new EntityId('test', '123'), 'Some data')]);

        $struct = $mapper->map($req);

        $expected = [
            'entities' => [
                [
                    'id' => ['type' => 'test', 'id' => '123'],
                    'data' => 'U29tZSBkYXRh', // Base 64 encoded
                    'deleted' => false
                ]
            ]
        ];

        $this->assertEquals($expected, $struct);

        $unmapped = $mapper->unmap($struct, WriteRequest::class);

        $this->assertEquals($req, $unmapped);
    }

    public function dataProvider()
    {
        return [
            [
                ['id' => 123, 'type' => 'test'],
                EntityId::class
            ],
            [
                ['id' => ['id' => 234, 'type' => 'test'], 'data' => 'Some data', 'deleted' => false],
                Entity::class
            ],
            [
                [],
                SignalRequest::class
            ],
            [
                ['status' => true, 'error' => null, 'signal' => ['ids' => [['type' => 'test', 'id' => '123']]]],
                SignalResponse::class
            ],
            [
                ['ack' => true],
                SignalAckRequest::class
            ],
            [
                ['status' => true, 'error' => null],
                SignalAckResponse::class
            ],
            [
                ['ids' => [['type' => 'test', 'id' => '123']]],
                ReadRequest::class
            ],
            [
                [
                    'status' => true,
                    'error' => null,
                    'entities' => [
                        ['id' => ['type' => 'test', 'id' => '123'], 'data' => 'Some data', 'deleted' => false]
                    ]
                ],
                ReadResponse::class
            ],
            [
                ['entities' => [['id' => ['type' => 'test', 'id' => '123'], 'data' => 'Some data', 'deleted' => false]]],
                WriteRequest::class
            ],
            [
                ['status' => true, 'error' => null],
                WriteResponse::class
            ],
        ];
    }
}
