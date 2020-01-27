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
<<<<<<< HEAD
        fwrite($this->connection, 'set\'$key\' \'$serialized\'');
=======
        $data = $serialized[$key];
        fwrite($this->connection, 'set(\'$key\', \'$data\'');
>>>>>>> efffb4e07adac0643f605883468d9f374a1e7991
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
<<<<<<< HEAD
        return unserialize(fwrite($this->connection, 'get \'$key\''));
=======
        fwrite($this->connection, 'get(\'$key\')');
        
>>>>>>> efffb4e07adac0643f605883468d9f374a1e7991
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