<?php
namespace Buybrain\Nervus\Adapter;

use Buybrain\Nervus\Adapter\Config\AdapterConfig;
use Buybrain\Nervus\Adapter\Config\ExtraAdapterConfig;
use Buybrain\Nervus\Adapter\Config\NoExtraConfig;
use Buybrain\Nervus\Codec\Codec;
use Buybrain\Nervus\Codec\Decoder;
use Buybrain\Nervus\Codec\Encoder;
use Buybrain\Nervus\Codec\JsonEncoder;
use Buybrain\Nervus\Codec\MessagePackCodec;
use Buybrain\Nervus\Util\Streams;
use RuntimeException;

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
     * @param $addr string
     * @return $this
     */
    public function socketAddr($addr)
    {
        $socket = stream_socket_client('tcp://' . $addr);
        if ($socket === false) {
            throw new RuntimeException('Could not dial to ' . $addr . ': ' . socket_strerror(socket_last_error()));
        }
        return $this->io($socket);
    }

    /**
     * @param resource $stream
     * @return $this
     */
    public function io($stream)
    {
        return $this->in($stream)->out($stream);
    }

    /**
     * @param resource $input
     * @return $this
     */
    public function in($input)
    {
        $this->input = Streams::assertStream($input);
        return $this;
    }

    /**
     * @param resource $output
     * @return $this
     */
    public function out($output)
    {
        $this->output = Streams::assertStream($output);
        return $this;
    }

    /**
     * @param Codec $codec
     * @return $this
     */
    public function codec(Codec $codec)
    {
        $this->codec = $codec;
        return $this;
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

    /**
     * @return string
     */
    abstract protected function getAdapterType();
    
    /**
     * @return string[]|null
     */
    abstract public function getSupportedEntityTypes();

    /**
     * @return ExtraAdapterConfig
     */
    protected function getExtraConfig()
    {
        return null;
    }

    private function init()
    {
        if ($this->encoder === null) {
            $config = new AdapterConfig(
                $this->codec->getName(), 
                $this->getAdapterType(), 
                $this->getSupportedEntityTypes(),
                $this->getExtraConfig()
            );
            (new JsonEncoder($this->output))->useNewlines(false)->encode($config);
            $this->decoder = $this->codec->newDecoder($this->input);
            $this->encoder = $this->codec->newEncoder($this->output);
        }
    }
}
