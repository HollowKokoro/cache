<?php
declare(strict_types=1);

class CacheFile implements CacheInterface
{
    /**
     * Путь к файлу
     */
    public string $path;

    /**
     * Конструктор
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, array $value): void
    {
        $data = $this->read();
        $data[$key] = $value;
        $this->write($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $key)
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

    /**
     * Проверяет файл на существование и получает ансериализованное значение
     */
    private function read(): array
    {
        if (!is_readable($this->path)) {
            throw new RuntimeException();
        }

        $content = file_get_contents($this->path);
        $unserialized = unserialize($content);
        if (!is_array($unserialized)) {
            $unserialized = [];
        }

        return $unserialized;
    }

    /**
     * Проверяет файл на существование и пишет сериализованное значение
     * @param  mixed $data Содержит значение для записи
     */
    private function write($data): void
    {
        if (!is_readable($this->path)) {
            throw new RuntimeException();
        }

        $serialized = serialize($data);
        $newData = file_put_contents($this->path, $serialized);
        
        if ($newData === false) {
            throw new RuntimeException();
        }
    }
}
