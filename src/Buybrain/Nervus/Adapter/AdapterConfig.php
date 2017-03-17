<?php
namespace Buybrain\Nervus\Adapter;

use JsonSerializable;

class AdapterConfig implements JsonSerializable
{
    /** @var string */
    private $codec;

    /**
     * @param string $codec
     */
    public function __construct($codec)
    {
        $this->codec = $codec;
    }

    public function getCodec()
    {
        return $this->codec;
    }


    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'Codec' => $this->codec,
        ];
    }
}
