<?php
namespace Buybrain\Nervus\Util;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use PHPUnit_Framework_TestCase;

class ObjectsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     * @param mixed $input
     * @param mixed $expectedOutput
     */
    public function testToPrimitiveOrStruct($input, $expectedOutput)
    {
        $output = Objects::toPrimitiveOrStruct($input);
        $this->assertEquals($expectedOutput, $output);
    }

    public function dataProvider()
    {
        return [
            [1, 1],
            [null, null],
            [true, true],
            ['test', 'test'],
            [[1, 2, 3], [1, 2, 3]],
            [['a' => 1], ['a' => 1]],
            [(object)['a' => 1], ['a' => 1]],
            [
                new Entity(new EntityId('test', '123'), 'aaa'),
                ['Id' => ['Type' => 'test', 'Id' => '123'], 'Data' => 'aaa', 'Deleted' => false]
            ],
        ];
    }
}
