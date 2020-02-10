<?php
declare(strict_types=1);

class ValueFound implements ValueInterface
{
    private $data;

    public function __construct()
    {
        
    }

    public function isFound($value): bool
    {
        return isset($this->data, $value);
    }

    public function getValue($value)
    {
        if ($this->isFound($value) === True)
        {
            return "Error. \"$this->data\" = \"$value\".";
        }
    }
}