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
     * @param $path - путь к директории
     * @return void
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value) : void
    {
        $data = $this->read();
        $data[$key] = $value;
        $this->write($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
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
    public function remove($key) : void
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
    private function read() : array
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
     * @param  mixed $data - содержит сериализованное значение
     */
    private function write($data) : void
    {
        if (!is_readable($this->path)) {
            throw new RuntimeException();
        }

        $serialized = serialize($data);
        $new_data = file_put_contents($this->path, $serialized);
        
        if ($new_data === false) {
            throw new RuntimeException();
        }
    }
}
