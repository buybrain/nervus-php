<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Codec\Mapper\StructMapper;
use MessagePack\Packer;

/**
 * Decoder that writes MessagePack encoded messages using a pure PHP implementation
 */
class PureMessagePackEncoder extends AbstractEncoder
{
    /** @var Packer */
    private $packer;

    /**
     * @param resource $stream
     * @param StructMapper $mapper
     */
    public function __construct($stream, StructMapper $mapper)
    {
        parent::__construct($stream, $mapper);
        $this->packer = new Packer();
    }

    /**
     * @param $data
     * @return string
     */
    protected function serialize($data)
    {
        return $this->packer->pack($data);
    }
}
