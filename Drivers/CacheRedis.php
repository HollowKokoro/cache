<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    private $connection;

    public function __construct($host, $port)
    {
        $this->connection = new Redis($host, $port);
    }

    public function set(string $key, $value): void
    {
        $serialized = serialize($value);
        $this->connection->set($key, $serialized);
    }

    public function get($key)
    {   
        if (array_key_exists($key, $this->connection)) {
            return $this->connection($key);
        } else {
            return null;
        }
    }
}