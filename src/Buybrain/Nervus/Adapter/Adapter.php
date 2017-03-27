<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Codec\Codec;
use Buybrain\Nervus\Codec\Decoder;
use Buybrain\Nervus\Codec\Encoder;
use Buybrain\Nervus\Codec\JsonEncoder;
use Buybrain\Nervus\Codec\MessagePackCodec;
use Buybrain\Nervus\Util\Streams;

/**
 * Base class for all nervus adapters. It handles communication with the nervus host.
 */
abstract class Adapter
{
    /** @var Codec */
    private $codec;
    /** @var resource */
    private $input;
    /** @var resource */
    private $output;
    /** @var Encoder */
    protected $encoder;
    /** @var Decoder */
    protected $decoder;

    public function __construct()
    {
        $this->codec = new MessagePackCodec();
    }

    /**
     * Set a stream for both input and output
     *
     * @param resource $stream
     * @return $this
     */
    public function io($stream)
    {
        return $this->in($stream)->out($stream);
    }

    /**
     * Set a stream for input
     *
     * @param resource $input
     * @return $this
     */
    public function in($input)
    {
        $this->input = Streams::assertStream($input);
        return $this;
    }

    /**
     * Set a stream for output
     *
     * @param resource $output
     * @return $this
     */
    public function out($output)
    {
        $this->output = Streams::assertStream($output);
        return $this;
    }

    /**
     * Specify a codec to use for communicating with the nervus host
     *
     * @param Codec $codec
     * @return $this
     */
    public function codec(Codec $codec)
    {
        $this->codec = $codec;
        return $this;
    }

    /**
     * Starts the adapter and keeps handling requests in a loop. This method never returns and will typically be
     * the last call in an adapter implementation script.
     */
    public function run()
    {
        $this->init();
        while (true) {
            $this->doStep();
        }
    }

    /**
     * Perform a single step, i.e. handle a single incoming request
     */
    public function step()
    {
        $this->init();
        $this->doStep();
    }

    abstract protected function doStep();

    /**
     * @return string
     */
    abstract protected function getAdapterType();

    /**
     * @return ExtraAdapterConfig
     */
    protected function getExtraConfig()
    {
        return null;
    }

    /**
     * Prepare this adapter for communication. Will send the adapter configuration to the nervus host and set up an
     * encoder and decoder for further communication.
     */
    private function init()
    {
        if ($this->encoder === null) {
            $config = new AdapterConfig(
                $this->codec->getName(),
                $this->getAdapterType(),
                $this->getExtraConfig()
            );
            (new JsonEncoder($this->output))->useNewlines(false)->encode($config);
            $this->decoder = $this->codec->newDecoder($this->input);
            $this->encoder = $this->codec->newEncoder($this->output);
        }
    }
}
