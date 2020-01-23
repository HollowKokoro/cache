<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    /**
     * @var string $connection Соединяет с Redis
     */ 
    private $connection;

    /**
     * Конструктор
     * @param  string $host
     * @param  int $port
     * @param  string $select
     */
    public function __construct(string $host, int $port, string $select)
    {
        $this->connection = new Redis();
        $this->connection->connect($host, $port, $select);
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
        if (!$this->connection->exists($key)) {
            return null;
        }
        $unserialized = unserialize($this->connection->get($key));
        return $unserialized;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $this->connection->del($key);
    }
}
