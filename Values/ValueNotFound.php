<?php
declare(strict_types=1);
require_once "/home/kokoro/Cache/Interfaces/ValueInterface.php";

class ValueNotFound implements ValueInterface
{
    public function isFound(): bool
    {
        return false;
    }

    public function getValue()
    {
        return get_class($this);
    }
} 
