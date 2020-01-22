<?php
declare(strict_types=1);

class CacheRedis implements CacheInterface
{
    $connection = new Redis();

    /**
     * Проверяет файл на существование и получает десериализованное значение
     * @return десериализованный массив
     */
    private function read(): array
    {
        if (!is_readable($this->connection)) {
            throw new RuntimeException();
        }

        $content = file_get_contents($this->connection);
        $unserialized = unserialize($content);
        if (!is_array($unserialized)) {
            $unserialized = [];
        }

        return $unserialized;
    }

    /**
     * Проверяет файл на существование и пишет сериализованное значение
     * @param  array $data Содержит значение для записи
     */
    private function write(array $data): void
    {
        if (!is_readable($this->connection)) {
            throw new RuntimeException();
        }

        $serialized = serialize($data);
        $newData = file_put_contents($this->connection, $serialized);
        
        if ($newData === false) {
            throw new RuntimeException();
        }
}