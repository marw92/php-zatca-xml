<?php

namespace Saleh7\Zatca;

class Tag
{
    protected int $tag;

    protected string $value;

    public function __construct($tag, $value)
    {
        $this->tag = $tag;
        $this->value = $value;
    }

    public function getTag(): int
    {
        return $this->tag;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * its important to get the number of bytes of a string instated of number of characters
     */
    public function getLength(): int
    {
        return strlen($this->value);
    }

    /**
     * @return string Returns a string representing the encoded TLV data structure.
     */
    public function __toString()
    {
        $value = (string) $this->getValue();

        return $this->toHex($this->getTag()).$this->toHex($this->getLength()).($value);
    }

    /**
     * To convert the string value to hex.
     */
    protected function toHex($value): string
    {
        return pack('H*', sprintf('%02X', $value));
    }
}
