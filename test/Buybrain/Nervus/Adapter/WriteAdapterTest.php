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
        $request = new WriteRequest([new Entity(new EntityId('test', 123), 'test')]);

        $io = (new TestIO())->write($request);

        $SUT = (new MockWriteAdapter())->in($io->input())->out($io->output())->codec($io->codec());

        $SUT->step();

        $expected =
            json_encode(new AdapterConfig($io->codec()->getName(), 'write', ['test'])) .
            $io->encode(WriteResponse::success());

        $this->assertEquals($expected, $io->writtenData());
    }
}
