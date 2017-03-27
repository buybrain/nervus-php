<?php
namespace Buybrain\Nervus\Codec;

use Buybrain\Nervus\Util\Objects;
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
     */
    public function __construct($stream)
    {
        parent::__construct($stream);
        $this->packer = new Packer();
    }

    /**
     * @param $data
     * @return string
     */
    protected function serialize($data)
    {
        return $this->packer->pack(Objects::toPrimitiveOrStruct($data));
    }
}
