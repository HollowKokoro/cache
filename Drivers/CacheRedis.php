<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    private $connection;

    public function __construct($host, $port)
    {
        $this->connection = new Redis();
        $this->connection->connect($host, $port);
    }

    public function set(string $key, $value): void
    {
        $serialized = serialize($value);
        $this->connection->set($key, $serialized);
    }

    public function get(string $key)
    {
        if (!$this->connection->exists($key)) {
            return null;
        } else {
            $unserialized = unserialize($this->connection->get($key));
            return $unserialized;
        }
    }
    public function remove(string $key): void
    {
        $delete = unserialize($this->connection->del($key));
    }
}
