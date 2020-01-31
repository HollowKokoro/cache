<?php
declare(strict_types=1);

class CacheRedisFsocket implements CacheInterface
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

    public function save($command): void
    {
        fwrite($this->connection, $command);
        fgets($this->connection, 1024);
    }

    public function replace ($value)
    {
        return str_replace("\"Hello\"", "\\\"Hello\\\"", serialize($value));
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): void
    {
        
        echo $command = sprintf("SET %s \"%s\"\n", $key, $this->replace($value));
        $this->save($command);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $command = sprintf("GET %s\n", $key);
        $this->save($command);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $command = sprintf("DEL %s\n", $key);
        $this->save($command);
    }
}