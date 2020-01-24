<?php

class CacheRedis implements CacheInterface
{
    /**
     * @var Redis $connection Соединяет с Redis
     */
     private $connection;

    /**
     * Конструктор
     * @param  string $host Имя хоста
     * @param  int $port Номер порта
     */

    public function __construct(string $host, int $port)
    {
        $this->connection = fsockopen($host, $port);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): void
    {
        $serialized = serialize($value);
        $this->connection->set($key, $serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $getValue = $this->connection->get($key);
        if ($getValue === false) {
            return null;
        }
        return unserialize($getValue);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $this->connection->del($key);
    }
}