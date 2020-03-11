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
     * @throws RuntimeException если $dbNumber больше, чем максимальное число БД Redis, которое = 16
     * @throws RuntimeException если получает ответ от Redis "-ERR", при подключении к выбранному номеру БД Redis
     */
    public function __construct(string $host, int $port, int $dbNumber)
    {
        if ($dbNumber > 16) {
            throw new RuntimeException("Максимальное число баз данных Redis 16. \"$dbNumber\" должно быть меньше 16");
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
     * @throws RuntimeException если пользователт передаёт $ttl, который не равен null и меньше или равен 0
     * @throws RuntimeException если получает ответ от Redis отличный от "+OK", что означает, произошла ошибка операции SET
     * @throws RuntimeException если получает ответ от Redis отличный от ":1", что означает произошла ощибка операции EXPIRE
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
            throw new RuntimeException("Ошибка выполнения операции SET $result);
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
        if ($extracted !== -1) {
            $serialized = fread($this->connection, $extracted);
            $data = unserialize($serialized);
            return new ValueFound($data);
        }
        return new ValueNotFound();
    }

    /**
     * {@inheritdoc}
     * @throws RuntimeException если получает ответ от Redis отличный от "-1", что означает произошла ощибка операции DEL
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
     * Конвертирует строковый ответ Redis на выполненную операцию для последующего сравнения в int
     * @param  string $redisResult Статус Redis в ответ на операцию
     * @return int Ответ Redis (если может быть выражен в int)
     */
    private function extractNumber(string $redisResult): int
    {
        return (int)str_replace(["$", "\r", "\n"], "", $redisResult);
    }
}
