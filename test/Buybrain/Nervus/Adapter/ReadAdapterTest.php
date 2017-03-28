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

        $expected = '{"Codec":"json","AdapterType":"read","Extra":{"EntityTypes":null}}' .
            '{"Status":true,"Error":null,"Entities":[{"Id":{"Type":"test","Id":"123"},' .
            '"Data":"dGVzdA==","Deleted":false}]}';

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

        $expected = '{"Codec":"json","AdapterType":"read","Extra":{"EntityTypes":["type1","type2"]}}' .
            '{"Status":true,"Error":null,"Entities":[' .
            '{"Id":{"Type":"type1","Id":"456"},"Data":"Y29udGVudDE=","Deleted":false},' .
            '{"Id":{"Type":"type2","Id":"234"},"Data":"Y29udGVudDI=","Deleted":false}]}';

        $this->assertEquals($expected, $io->writtenData());
    }

    public function testUnsupportedType()
    {
        $request = new ReadRequest([new EntityId('unsupportedType', '234')]);

        $io = (new MockIO())->write($request);

        $read = $this->mapToStaticContentReader('test', ['type1']);
        $SUT = (new ReadAdapter())->in($io->input())->out($io->output())->codec($io->codec())->add($read);

        $SUT->step();

        $expected = '{"Codec":"json","AdapterType":"read","Extra":{"EntityTypes":["type1"]}}' .
            '{"Status":false,"Error":"Encountered unsupported types unsupportedType (supported: type1)",' .
            '"Entities":null}';

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
