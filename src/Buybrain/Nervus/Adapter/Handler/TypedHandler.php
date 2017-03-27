<?php
namespace Buybrain\Nervus\Adapter\Handler;

interface TypedHandler
{
    /**
     * @return string[]|null
     */
    public function getSupportedEntityTypes();
}
