<?php
declare(strict_types=1);

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
     * @param  int $dbNumber Номер БД
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
