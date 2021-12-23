<?php
namespace Buybrain\Nervus\Util;

use Buybrain\Nervus\EntityId;
use PHPUnit\Framework\TestCase;

class EntityIdsTest extends TestCase
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
