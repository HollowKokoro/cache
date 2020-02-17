<?php
declare(strict_types=1);

class ValueNotFound implements ValueInterface
{
    public function isFound(): bool
    {
        return false;
    }

    public function getValue()
    {
        
    }
} 
