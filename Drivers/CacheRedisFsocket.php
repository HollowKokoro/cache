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
        $data = $this->read();
        $data[$key] = $value;
        $this->write($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        $data = $this->read();
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $data = $this->read();
        if (array_key_exists($key, $data)) {
            unset($data[$key]);
        }
        $this->write($data);
    }

    private function read(): array
    {
        if (!is_readable($this->connection)) {
            throw new RuntimeException();
        }

        $content = fread($this->connection, 100);
        $unserialized = unserialize($content);
        return $unserialized;
    }

    private function write(array $data): void
    {
        if (!is_readable($this->connection)) {
            throw new RuntimeException();
        }

        $serialized = serialize($data);
        $newData = fwrite($this->path, $serialized);
        
        if ($newData === false) {
            throw new RuntimeException();
        }
    }
}