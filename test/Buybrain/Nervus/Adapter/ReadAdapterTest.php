<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\TestIO;
use PHPUnit_Framework_TestCase;

class ReadAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testExtendingReadAdapter()
    {
        $entityId = new EntityId('test', '123');
        $request = new ReadRequest([$entityId]);

        $io = (new TestIO())->write($request);

        $SUT = (new MockReadAdapter())->in($io->input())->out($io->output())->codec($io->codec());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'read', ['test'])) .
            $io->encode(ReadResponse::success([new Entity($entityId, 'test')]));

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testComposingReadAdapter()
    {
        $id1 = new EntityId('type1', '456');
        $id2 = new EntityId('type2', '234');
        $request = new ReadRequest([$id1, $id2]);

        $io = (new TestIO())->write($request);

        $SUT = ReadAdapter::compose()
            ->in($io->input())
            ->out($io->output())
            ->codec($io->codec())
            ->type('type1', function (array $ids) {
                return $this->mapToStaticContent($ids, 'content1');
            })
            ->type('type2', function (array $ids) {
                return $this->mapToStaticContent($ids, 'content2');
            });

        $this->assertEquals(['type1', 'type2'], $SUT->getSupportedEntityTypes());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'read', ['type1', 'type2'])) .
            $io->encode(ReadResponse::success([new Entity($id1, 'content1'), new Entity($id2, 'content2')]));

        $this->assertEquals($expected, $io->writtenData());
    }

    /**
     * @param EntityId[] $ids
     * @param $content
     * @return Entity[]
     */
    public function mapToStaticContent(array $ids, $content)
    {
        return array_map(function (EntityId $id) use ($content) {
            return new Entity($id, $content);
        }, $ids);
    }
}
