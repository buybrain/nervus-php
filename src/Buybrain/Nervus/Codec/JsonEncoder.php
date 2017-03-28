<?php
namespace Buybrain\Nervus\Codec;

/**
 * Encoder that writes JSON encoded messages
 */
class JsonEncoder extends AbstractEncoder
{
    /** @var bool */
    private $useNewlines = true;

    /**
     * @param bool $useNewlines whether to insert newlines between consecutive messages
     * @return $this
     */
    public function useNewlines($useNewlines)
    {
        $this->useNewlines = $useNewlines;
        return $this;
    }

    /**
     * @param $data
     * @return string
     */
    protected function serialize($data)
    {
        return json_encode($data) . ($this->useNewlines ? "\n" : '');
    }
}
