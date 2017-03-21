<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Exception\Exception;
use Buybrain\Nervus\Util\Streams;
use PHPUnit_Framework_TestCase;

class StreamsTest extends PHPUnit_Framework_TestCase
{
    public function testAssertStreamValid()
    {
        Streams::assertStream(STDIN);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider noStreamProvider
     * @expectedException Exception
     * @param $noStream
     */
    public function testAssertStreamInvalid($noStream)
    {
        Streams::assertStream($noStream);
    }
    
    public function noStreamProvider()
    {
        return [
            [true],
            [sem_get(0)]
        ];
    }
}
