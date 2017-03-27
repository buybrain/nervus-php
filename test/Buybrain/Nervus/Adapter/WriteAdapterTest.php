<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Adapter\Handler\CallableWriter;
use Buybrain\Nervus\Adapter\Message\WriteRequest;
use Buybrain\Nervus\Adapter\Message\WriteResponse;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\TestIO;
use PHPUnit_Framework_TestCase;

class WriteAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testWriteAdapter()
    {
        $request = new WriteRequest([
            new Entity(new EntityId('type1', '123'), 'test'),
            new Entity(new EntityId('type2', '234'), 'test'),
        ]);

        $io = (new TestIO())->write($request);

        $receivedEntities1 = [];
        $receivedEntities2 = [];

        $SUT = (new WriteAdapter())
            ->add(new CallableWriter(
                function (array $entities) use (&$receivedEntities1) {
                    $receivedEntities1 = array_merge($receivedEntities1, $entities);
                },
                ['type1']
            ))
            ->add(new CallableWriter(
                function (array $entities) use (&$receivedEntities2) {
                    $receivedEntities2 = array_merge($receivedEntities2, $entities);
                },
                ['type2']
            ))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $this->assertEquals([$request->getEntities()[0]], $receivedEntities1);
        $this->assertEquals([$request->getEntities()[1]], $receivedEntities2);

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'write', ['type1', 'type2'])) .
            $io->encode(WriteResponse::success());

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testErrorWriteWriting()
    {
        $request = new WriteRequest([new Entity(new EntityId('type1', '123'), 'test')]);

        $io = (new TestIO())->write($request);

        $SUT = (new WriteAdapter())
            ->add(new CallableWriter(function (array $entities) {
                throw new Exception('Wow');
            }))
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'write', null)) .
            $io->encode(WriteResponse::error(new Exception('Wow')));

        $this->assertEquals($expected, $io->writtenData());
    }
}
