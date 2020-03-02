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
     * @param  int $dbNumber Индекс БД
     */
    public function __construct(string $host, int $port, int $dbNumber)
    {
        $this->connection = fsockopen($host, $port);
        $result=$this->save(sprintf("SELECT \"%d\"\n", $dbNumber));
        $checkError = substr($result, 0, 4);
        if ($checkError  === "-ERR") {
            throw new RuntimeException($result);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): void
    {
        $serialized = serialize($value);
        $serializedNew = $this->replace($serialized);
        $keyNew = $this->replace($key);
        $command = sprintf("SET \"%s\" \"%s\"\n", $keyNew, $serializedNew);
        $result = $this->save($command);
        if ($result !== "+OK\r\n") {
            throw new RuntimeException($result);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        $keyNew = $this->replace($key);
        $command = sprintf("GET \"%s\"\n", $keyNew);
        $result = $this->save($command);
        $extracted = $this->extractNumber($result);
        if ($extracted !== -1) {
            $serialized = fread($this->connection, $extracted);
            $data = unserialize($serialized);
            $this->expiration($key, $ttlSeconds);
            return new ValueFound($data);
        } 
        return new ValueNotFound();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $keyNew = $this->replace($key);
        $command = sprintf("DEL \"%s\"\n", $keyNew);
        $result = $this->save($command);
        $extracted = $this->extractNumber($result);
        if ($extracted === -1) {
            throw new RuntimeException($result);
        }
    }

    /**
     * save Передаёт значение в Redis и возвращает ответ
     * @param  string $command Значение передаваемое в Redis
     * @return string Ответ Redis-а
     */
    private function save(string $command): string
    {
        fwrite($this->connection, $command);
        return fgets($this->connection, 1024);
    }

    /**
     * replace Приводит строку к читаемому виду для Redis
     * @param  mixed $command Пользовательские данные
     * @return string Конвертированные данные
     */
    private function replace($command): string
    {
        return str_replace("\"", "\\\"", $command);
    }

    /**
     * extract_number Конвертирует строку в integer
     * @param  mixed $redisResult Статус Redis в string
     * @return int Количество байт в integer
     */
    private function extractNumber(string $redisResult): int
    {
        return (int)str_replace(["$", "\r", "\n"], "", $redisResult);
    }

    /**
     * expiration Присвает время жизни ключа
     * @param string $key Ключ массива
     * @param int $ttlSeconds Время жизни кюча в секундах
     */
    private function expiration(string $key, int $ttlSeconds): void
    {
        $keyNew = $this->replace($key);
        $ttlMilliseconds = $ttlSeconds * 1000;
        $command = sprintf("EXPIRE \"%s\" \"%b\"\n", $keyNew, $ttlMilliseconds);
        $result = $this->save($command);
        $extracted = $this->extractNumber($result);
        if ($extracted === -1) {
            throw new RuntimeException($result);
        }
    }
}
