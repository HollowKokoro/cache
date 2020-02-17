<?php
declare(strict_types=1);
require_once "/home/kokoro/Cache/Interfaces/ValueInterface.php";

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