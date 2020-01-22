<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    public function __construct()
    {
        $this->connection = new Redis();
    }

    public function set(string $key, $value): void
    {
        $this->connection->$key = $value;
    }

    public function get(string $key)
    {
        if (array_key_exists($key, $this->connection)) {
            return $this->connection->$key;
        } else {
            return null;
        }
    }

    public function remove(string $key): void
    {
        unset($this->connection->$key);
    }
}