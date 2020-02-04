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
    public function get(string $key)
    {
        $keyNew = $this->replace($key);
        $command = sprintf("GET \"%s\"\n", $keyNew);
        $result = $this->save($command);
        var_dump($result);
        /*string(5) "$12
"
$12
*/
        if ($result === "$-1\r\n") {
            return null;
        }
        echo $this->extract_number($result);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $keyNew = $this->replace($key);
        $command = sprintf("DEL \"%s\"\n", $keyNew);
        $result = $this->save($command);
        if ($result === "$-1\r\n") {
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

    private function extract_number (string $redisResult): int
    {
        return intval(preg_replace($redisResult, '', '$'));
    }
}
