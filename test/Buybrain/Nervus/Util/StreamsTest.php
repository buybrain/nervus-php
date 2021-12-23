<?php
namespace Buybrain\Nervus\Util;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StreamsTest extends TestCase
{
    public function testAssertStreamValid()
    {
        Streams::assertStream(STDIN);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider noStreamProvider
     * @param $noStream
     */
    public function testAssertStreamInvalid($noStream)
    {
        $this->expectException(InvalidArgumentException::class);
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
