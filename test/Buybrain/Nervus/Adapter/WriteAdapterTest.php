<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Handler\CallableWriter;
use Buybrain\Nervus\Adapter\Message\WriteRequest;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\MockIO;
use PHPUnit\Framework\TestCase;

class WriteAdapterTest extends TestCase
{
    public function testWriteAdapter()
    {
        $request = new WriteRequest([
            new Entity(new EntityId('type1', '123'), 'test'),
            new Entity(new EntityId('type2', '234'), 'test'),
        ]);

        $io = (new MockIO())->write($request);

        $receivedEntities1 = [];
        $receivedEntities2 = [];

        $write1 = new CallableWriter(
            function (array $entities) use (&$receivedEntities1) {
                $receivedEntities1 = array_merge($receivedEntities1, $entities);
            },
            ['type1']
        );
        $write2 = new CallableWriter(
            function (array $entities) use (&$receivedEntities2) {
                $receivedEntities2 = array_merge($receivedEntities2, $entities);
            },
            ['type2']
        );

        $SUT = (new WriteAdapter())
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec())
            ->add($write1)
            ->add($write2);

        $SUT->maxRequests(1)->run();

        $this->assertEquals([$request->getEntities()[0]], $receivedEntities1);
        $this->assertEquals([$request->getEntities()[1]], $receivedEntities2);

        $expected = '{"codec":"json","adapterType":"write","extra":{"entityTypes":["type1","type2"]}}' .
            '{"status":true,"error":null}';

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testErrorWriteWriting()
    {
        $request = new WriteRequest([new Entity(new EntityId('type1', '123'), 'test')]);

        $io = (new MockIO())->write($request);

        $SUT = (new WriteAdapter())
            ->add(new CallableWriter(function (array $entities) {
                throw new Exception('Wow');
            }))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->maxRequests(1)->run();

        $expected = '{"codec":"json","adapterType":"write","extra":{"entityTypes":null}}' .
            '{"status":false,"error":"Wow"}';

        $this->assertEquals($expected, $io->writtenData());
    }
}
