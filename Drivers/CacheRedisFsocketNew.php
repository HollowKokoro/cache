<?php
declare(strict_types=1);

class CacheRedisFsocketNew implements CacheInterface
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
        $valueString = strval($value);
        $command = sprintf("SET %s \"%s\"\n", $key, $valueString);
        fwrite($this->connection, $command);
        fgets($this->connection, 1024);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $command = sprintf("GET %s\n", $key);
        fwrite($this->connection, $command);
        fgets($this->connection, 1024);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $command = sprintf("DEL %s\n", $key);
        fwrite($this->connection, $command);
        fgets($this->connection, 1024);
    }
}