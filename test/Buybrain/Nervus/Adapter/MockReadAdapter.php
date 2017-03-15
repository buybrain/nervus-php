<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Entity;
use Buybrain\Nervus\EntityId;
use Exception;

class MockReadAdapter extends ReadAdapter
{
    /** @var string */
    private $mockData;

    /**
     * @param string $mockData
     */
    public function __construct($mockData = 'test')
    {
        parent::__construct();
        $this->mockData = $mockData;
    }

    /**
     * @param EntityId[] $ids
     * @return Entity[]
     * @throws Exception
     */
    public function onRequest(array $ids)
    {
        return array_map(function (EntityId $id) {
            return new Entity($id, $this->mockData);
        }, $ids);
    }
}
