<?php
declare(strict_types=1);

interface ValueInterface
{
    public function isFound(string $key): bool;

    public function getValue(string $key);
}