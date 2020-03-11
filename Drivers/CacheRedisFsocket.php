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
        $result = $this->save(sprintf("SELECT \"%d\"\n", $dbNumber));
        $checkError = substr($result, 0, 4);
        if ($checkError === "-ERR") {
            throw new RuntimeException($result);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, ?int $ttl = null): void
    {
        if ($ttl <= 0 || $ttl === null) {
            throw new RuntimeException("Expected non-negative integer");
        }

        $keyNew = $this->correctView($key);
        $command = sprintf("SET \"%s\" \"%s\"\n", $keyNew, $this->correctView(serialize($value)));
        $result = $this->save($command);

        if ($result !== "+OK\r\n") {
            throw new RuntimeException($result);
        }

        if ($ttl === null) {
            return;
        }
        
        $commandTtl = sprintf("EXPIRE \"%s\" %d\n", $keyNew, $ttl);
        $resultTtl = $this->save($commandTtl);
        
        if ($resultTtl !== ":1\r\n") {
            throw new RuntimeException($resultTtl);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        $keyNew = $this->correctView($key);
        $command = sprintf("GET \"%s\"\n", $keyNew);
        $result = $this->save($command);
        $extracted = $this->extractNumber($result);
        if ($extracted !== -1) {
            $serialized = fread($this->connection, $extracted);
            $data = unserialize($serialized);
            return new ValueFound($data);
        }
        return new ValueNotFound();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $keyNew = $this->correctView($key);
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
     * correctView Приводит строку к читаемому виду для Redis
     * @param  mixed $command Пользовательские данные
     * @return string Конвертированные данные
     */
    private function correctView($command): string
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
}
