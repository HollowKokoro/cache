<?php
declare(strict_types=1);

class ValueNotFound implements ValueInterface
{
    public function isFound(): bool
    {
        return True;
    }

    public function getValue(): void
    {
        if ($this->isFound() === False)
        {
            return;
        }
    }
} 
