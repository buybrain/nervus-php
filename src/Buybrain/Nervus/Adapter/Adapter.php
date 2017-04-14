<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Codec\Codec;
use Buybrain\Nervus\Codec\Codecs;
use Buybrain\Nervus\Codec\Decoder;
use Buybrain\Nervus\Codec\Encoder;
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
    /** @var bool */
    private $singleRequest = false;

    public function __construct()
    {
        $this->codec = Codecs::messagePack();
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
     * Handle just one request and then stop the adapter
     *
     * @return $this
     */
    public function singleRequest()
    {
        $this->singleRequest = true;
        return $this;
    }

    /**
     * Starts the adapter and keeps handling requests in a loop. This method never returns and will typically be
     * the last call in an adapter implementation script.
     */
    public function run()
    {
        $this->init();

        // Loop forever, except when configured to handle a single request
        while (true) {
            $this->doStep();
            if ($this->singleRequest) {
                break;
            }
        }
    }

    /**
     * Perform a single step, process a single request
     */
    abstract protected function doStep();

    /**
     * @return string
     */
    abstract protected function getAdapterType();

    /**
     * @return ExtraAdapterConfig|null
     */
    abstract protected function getExtraConfig();

    /**
     * Prepare this adapter for communication. Will send the adapter configuration to the nervus host and set up an
     * encoder and decoder for further communication.
     */
    private function init()
    {
        if ($this->encoder === null) {
            // Send the adapter config as JSON payload to the host
            $config = new AdapterConfig(
                $this->codec->getName(),
                $this->getAdapterType(),
                $this->getExtraConfig()
            );
            Codecs::json()->newEncoder($this->output)->useNewlines(false)->encode($config);

            // Set up encoder / decoder for further communication
            $this->decoder = $this->codec->newDecoder($this->input);
            $this->encoder = $this->codec->newEncoder($this->output);
        }
    }
}
