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
     * @throws RuntimeException
     * @throws RuntimeException
     */
    public function __construct(string $host, int $port, int $dbNumber)
    {
        if ($dbNumber > 16 && $dbNumber < 0) {
            throw new RuntimeException("Максимальное число баз данных Redis 16. \"$dbNumber\" должно быть меньше 16 и больше 0");
        }
        $this->connection = fsockopen($host, $port);
        
        $result = $this->save(sprintf("SELECT %d\n", $dbNumber));

        $checkError = substr($result, 0, 4);
        if ($checkError === "-ERR") {
            throw new RuntimeException("Ошибка подключения к БД под номером \"$result\"");
        }
    }

    /**
     * {@inheritdoc}
     * @throws RuntimeException
     * @throws RuntimeException
     * @throws RuntimeException
     */
    public function set(string $key, $value, ?int $ttl = null): void
    {
        if ($ttl !== null && $ttl <= 0) {
            throw new RuntimeException("\"$ttl\" должно быть неотрицательным или null, если ключ бессрочный");
        }

        $safeKey = $this->correctView($key);
        $command = sprintf("SET \"%s\" \"%s\"\n", $safeKey, $this->correctView(serialize($value)));
        $result = $this->save($command);

        if ($result !== "+OK\r\n") {
            throw new RuntimeException("Ошибка выполнения операции SET $result");
        }

        if ($ttl === null) {
            return;
        }
        
        $ttlCommand = sprintf("EXPIRE \"%s\" %d\n", $safeKey, $ttl);
        $ttlResult = $this->save($ttlCommand);

        if ($ttlResult !== ":1\r\n") {
            throw new RuntimeException("Ошибка выполнения операции EXPIRE $result");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        $safeKey = $this->correctView($key);
        $command = sprintf("GET \"%s\"\n", $safeKey);
        $result = $this->save($command);

        $extracted = $this->extractNumber($result);
        if ($extracted === -1) {
            return new ValueNotFound();
        }
        $serialized = fread($this->connection, $extracted);
        $data = unserialize($serialized);
        return new ValueFound($data);
        

    }

    /**
     * {@inheritdoc}
     * @throws RuntimeException
     */
    public function remove(string $key): void
    {
        $safeKey = $this->correctView($key);
        $command = sprintf("DEL \"%s\"\n", $safeKey);
        $result = $this->save($command);

        $extracted = $this->extractNumber($result);
        if ($extracted === -1) {
            throw new RuntimeException("Ошибка выполнения операции DEL $result");
        }
    }

    /**
     * Передаёт значение в Redis и возвращает ответ Redis
     * @param  string $command Значение передаваемое в Redis
     * @return string Ответ Redis-а
     */
    private function save(string $command): string
    {
        fwrite($this->connection, $command);
        return fgets($this->connection, 1024);
    }

    /**
     * Экранирует пользовательские данные с целью безопасности
     * @param  mixed $command Пользовательские данные
     * @return string Конвертированные данные
     */
    private function correctView($command): string
    {
        return str_replace("\"", "\\\"", $command);
    }

    /**
     * Конвертирует строковый ответ Redis в int, чтобы это было возможно сравнить со статусом ошибки
     * @param  string $redisResult Статус Redis в ответ на операцию
     * @return int Ответ Redis (если может быть выражен в int)
     */
    private function extractNumber(string $redisResult): int
    {
        return (int)str_replace(["$", "\r", "\n"], "", $redisResult);
    }
}
