<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Handler\CallableReader;
use Buybrain\Nervus\Adapter\Handler\Reader;
use Buybrain\Nervus\Adapter\Message\ReadRequest;
use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Buybrain\Nervus\MockIO;
use PHPUnit_Framework_TestCase;

class ReadAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testSingleReader()
    {
        $entityId = new EntityId('test', '123');
        $request = new ReadRequest([$entityId]);

        $io = (new MockIO())->write($request);

        $read = $this->mapToStaticContentReader('test');
        $SUT = (new ReadAdapter())->in($io->input())->out($io->output())->codec($io->codec())->add($read);

        $SUT->step();

        $expected = '{"codec":"json","adapterType":"read","extra":{"entityTypes":null}}' .
            '{"status":true,"error":null,"entities":[{"id":{"type":"test","id":"123"},' .
            '"data":"dGVzdA==","deleted":false}]}';

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testMultipleReaders()
    {
        $id1 = new EntityId('type1', '456');
        $id2 = new EntityId('type2', '234');
        $request = new ReadRequest([$id1, $id2]);

        $io = (new MockIO())->write($request);

        $read1 = $this->mapToStaticContentReader('content1', ['type1']);
        $read2 = $this->mapToStaticContentReader('content2', ['type2']);
        $SUT = (new ReadAdapter())->in($io->input())->out($io->output())->codec($io->codec())->add($read1)->add($read2);

        $SUT->step();

        $expected = '{"codec":"json","adapterType":"read","extra":{"entityTypes":["type1","type2"]}}' .
            '{"status":true,"error":null,"entities":[' .
            '{"id":{"type":"type1","id":"456"},"data":"Y29udGVudDE=","deleted":false},' .
            '{"id":{"type":"type2","id":"234"},"data":"Y29udGVudDI=","deleted":false}]}';

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testUnsupportedType()
    {
        $request = new ReadRequest([new EntityId('unsupportedType', '234')]);

        $io = (new MockIO())->write($request);

        $read = $this->mapToStaticContentReader('test', ['type1']);
        $SUT = (new ReadAdapter())->in($io->input())->out($io->output())->codec($io->codec())->add($read);

        $SUT->step();

        $expected = '{"codec":"json","adapterType":"read","extra":{"entityTypes":["type1"]}}' .
            '{"status":false,"error":"Encountered unsupported types unsupportedType (supported: type1)",' .
            '"entities":null}';

        $this->assertEquals($expected, $io->writtenData());
    }

    /**
     * @param string $content
     * @param string[]|null $supportedTypes
     * @return Reader
     */
    private function mapToStaticContentReader($content, array $supportedTypes = null)
    {
        return new CallableReader(function ($ids) use ($content) {
            return array_map(function (EntityId $id) use ($content) {
                return new Entity($id, $content);
            }, $ids);
        }, $supportedTypes);
    }
}
