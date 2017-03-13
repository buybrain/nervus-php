<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Codec\Decoder;
use Buybrain\Nervus\Codec\Encoder;

abstract class AbstractAdapter
{
    /** @var AdapterContext */
    private $context;
    /** @var Encoder */
    protected $encoder;
    /** @var Decoder */
    protected $decoder;

    public function __construct(AdapterContext $context)
    {
        $this->context = $context;
    }

    public function run()
    {
        $this->init();
        while (true) {
            $this->doStep();
        }
    }

    public function step()
    {
        $this->init();
        $this->doStep();
    }

    abstract protected function doStep();

    private function init()
    {
        if ($this->encoder === null) {
            $codec = $this->context->getCodec();
            $this->decoder = $codec->newDecoder($this->context->getInput());
            $this->encoder = $codec->newEncoder($this->context->getOutput());
        }
    }
}
