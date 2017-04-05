<?php
namespace Buybrain\Nervus\Util;

use Buybrain\Nervus\EntityId;
use PHPUnit_Framework_TestCase;

class EntityIdsTest extends PHPUnit_Framework_TestCase
{
    public function testExtractIds()
    {
        $input = [
            new EntityId('a', '1'),
            new EntityId('b', '2'),
        ];

        $expected = ['1', '2'];

        $this->assertEquals($expected, EntityIds::extractIds($input));
    }
}