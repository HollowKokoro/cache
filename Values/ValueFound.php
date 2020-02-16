<?php
declare(strict_types=1);

class ValueFound implements ValueInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function isFound(): bool
    {
        return true;
    }

    public function getValue()
    {
        return $this->data;
    }
}