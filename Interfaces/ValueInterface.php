<?php
declare(strict_types=1);

interface ValueInterface
{
    public function isFound(): bool;

    public function getValue();
}