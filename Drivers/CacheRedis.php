<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    /**
     * @var mixed $connection Соединяет с Redis
     */ 
    private $connection;

    /**
     * Конструктор
     * @param  string $host
     * @param  int $port
     * @param  string $select
     */
    public function __construct(string $host, int $port, int $dbNumber)
    {
        $this->connection = new Redis();
        $this->connection->connect($host, $port);
        $this->connection->select($dbNumber);
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
