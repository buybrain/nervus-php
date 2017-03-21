<?php
namespace Buybrain\Nervus;

use Buybrain\Nervus\Codec\Codec;
use Buybrain\Nervus\Codec\JsonCodec;

class TestIO
{
    /** @var Codec */
    private $codec;
    /** @var resource */
    private $input;
    /** @var resource */
    private $output;

    public function __construct()
    {
        $this->codec = new JsonCodec();
        $this->input = fopen('php://temp', 'r+');
        $this->output = fopen('php://temp', 'r+');
    }

    /**
     * @return resource
     */
    public function input()
    {
        rewind($this->input);
        return $this->input;
    }

    /**
     * @return resource
     */
    public function output()
    {
        return $this->output;
    }

    /**
     * @return Codec
     */
    public function codec()
    {
        return $this->codec;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function write($data)
    {
        $this->codec->newEncoder($this->input)->encode($data);
        return $this;
    }

    /**
     * @param mixed $message , [$message, ...]
     * @return string
     */
    public function encode($message)
    {
        $messages = func_get_args();
        $stream = fopen('php://temp', 'r+');
        $enc = $this->codec->newEncoder($stream);
        foreach ($messages as $message) {
            $enc->encode($message);
        }
        rewind($stream);
        return stream_get_contents($stream);
    }

    /**
     * @return string
     */
    public function writtenData()
    {
        rewind($this->output);
        return stream_get_contents($this->output);
    }
}
