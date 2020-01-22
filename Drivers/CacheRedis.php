<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    private $connection;

    public function __construct('127.0.0.1', 6379)
    {
        $this->connection = new Redis();
    }

    public function set(string $key, $value): void
    {
        $fileData = file_get_contents($this->connection);
        $unserialized = @unserialize($fileData);
        if (!is_array($unserialized)) {
            $unserialized = [];
        }
        $unserialized->$key = $value;
        $serialized = serialize($unserialized);
        file_put_contents($this->connection, $serialized);
    }

    public function get($key)
    {   
        $file_data = file_get_contents($this->connection);
        $unserialized = unserialize($file_data);
        if (array_key_exists($key, $unserialized)) {
            return $unserialized[$key];
        }
        return null;
    }
    public function remove(string $key): void
    {
        
    }
}