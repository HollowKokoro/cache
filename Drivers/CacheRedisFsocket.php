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
        return $this->connection;
    }

   /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): void
    {
        $serialized = serialize($value);
        $data = $serialized[$key];
        fwrite($this->connection, 'set(\'$key\', \'$data\'');
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        fwrite($this->connection, 'get(\'$key\')');
        
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
    }     */
}