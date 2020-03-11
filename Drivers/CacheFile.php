<?php
declare(strict_types=1);

class CacheFile implements CacheInterface
{
    /**
     * @var string $path Путь к файлу
     */
    private string $path;

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
    public function set(string $key, $value, ?int $ttl = null): void
    {
        if ($ttl <= 0 || $ttl === null) {
            throw new RuntimeException("Expected non-negative integer");
        }
        $data = $this->read();
        $data[$key] = [
            'value' => $value,
            'ttl' => ($ttl !== null) ? time() + $ttl : INF,
        ];
        $this->write($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        $data = $this->read();
        if (!array_key_exists("value", $data[$key])) {
            return new ValueNotFound(); 
        }
        if (time() > $data[$key]["ttl"]) {
            $this->remove($key);
            return new ValueNotFound();
        } 
        return new ValueFound($data[$key]["value"]);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $data = $this->read();
        if (!array_key_exists($key, $data)) {
            return;
        }
        unset($data[$key]);
        $this->write($data);
    }

    /**
     * Проверяет файл на существование и получает десериализованное значение
     * @return десериализованный массив
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
     * @param  array $data Содержит значение для записи
     */
    private function write(array $data): void
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
