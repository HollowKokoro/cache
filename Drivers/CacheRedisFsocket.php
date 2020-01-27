<?php

class CacheRedis implements CacheInterface
{
    /**
     * @var $connection Соединяет с Redis
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
        $command = sprintf('set %s %s', $key, $serialized);
        fwrite($this->connection, $command);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $command = sprintf('get %s', $key);
        fwrite($this->connection, $command);
    }

    /**
     * {@inheritdoc}

    public function remove(string $key): void
    {
        $data = $this->read();
        if (array_key_exists($key, $data)) {
            unset($data[$key]);
        }
        $this->write($data);
    }*/
} 
