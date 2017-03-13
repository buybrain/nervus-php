<?php
namespace Buybrain\Nervus\Adapter;

class AdapterContext
{
    /** @var Codec */
    private $codec;
    /** @var resource */
    private $input;
    /** @var resource */
    private $output;

    /**
     * @param Codec $codec
     * @param resource $input
     * @param resource $output
     */
    public function __construct(Codec $codec, $input, $output)
    {
        Streams::assertStream($input);
        Streams::assertStream($output);
        $this->codec = $codec;
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return Codec
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * @return resource
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return resource
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return AdapterContext
     */
    public static function createDefault()
    {
        return new self(new JsonCodec(), fopen(STDIN, 'r'), fopen(STDOUT, 'w'));
    }
}
