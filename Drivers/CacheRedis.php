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
    public function set(string $key, $value, ?int $ttl = null): void
    {
            if ($ttl <= 0) {
                return;
            }
        $serialized = serialize($value);
        $this->connection->set($key, $serialized);
        $this->connection->expire($key, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        $getValue = $this->connection->get($key);
        if ($getValue === false) {
            return new ValueNotFound();
        }
        return new ValueFound(unserialize($getValue));
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $this->connection->del($key);
    }
}
