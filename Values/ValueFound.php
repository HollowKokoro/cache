<?php
declare(strict_types=1);

class ValueFound implements ValueInterface
{
    private $data;

    public function __construct()
    {
        $this->data;
    }

    public function isFound(): bool
    {
        return True;
    }

    public function getValue($value)
    {
        if ($this->isFound($value) === True)
        {
            return $this->data;
        }
    }
}