<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\TestIO;

class WriteAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteAdapter()
    {
        $request = new WriteRequest([new Entity(new EntityId('test', '123'), 'test')]);

        $io = (new TestIO())->write($request);

        $SUT = (new MockWriteAdapter())->in($io->input())->out($io->output())->codec($io->codec());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'write', ['test'])) .
            $io->encode(WriteResponse::success());

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testComposingWriteAdapter()
    {
        $request = new WriteRequest([
            new Entity(new EntityId('type1', '123'), 'test'),
            new Entity(new EntityId('type2', '234'), 'test'),
        ]);

        $io = (new TestIO())->write($request);

        $receivedEntities = [];

        $SUT = WriteAdapter::compose()
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec())
            ->type('type1', function (array $entities) use (&$receivedEntities) {
                $receivedEntities = array_merge($receivedEntities, $entities);
            })
            ->type('type2', function (array $entities) use (&$receivedEntities) {
                $receivedEntities = array_merge($receivedEntities, $entities);
            });

        $this->assertEquals(['type1', 'type2'], $SUT->getSupportedEntityTypes());

        $SUT->step();

        $this->assertEquals($request->getEntities(), $receivedEntities);

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'write', ['type1', 'type2'])) .
            $io->encode(WriteResponse::success());

        $this->assertEquals($expected, $io->writtenData());
    }
}
