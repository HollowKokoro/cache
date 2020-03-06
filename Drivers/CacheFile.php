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
        $data = $this->read();
        $data[$key] = $value;
        $ttlKey = $key. $key;

        if ($ttl !== null) {
            $data[$ttlKey] = time() + $ttl;
        } else {
            $data[$ttlKey] = INF;
        }
        $this->write($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        $ttlKey = $key. $key;
        $data = $this->read();
        if ($this->key_exists($key) === false) {
            return new ValueNotFound(); 
        }
        if (time() > $data[$ttlKey]) {
            $this->remove($key);
            return new ValueNotFound();
        } 
        return new ValueFound($data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        $ttlKey = $key. $key;
        $data = $this->read();
        if ($this->key_exists($key) === true) {
            unset($data[$key]);
        }
        unset($data[$ttlKey]);
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

    /**
     * Проверяет пользовательский ключ на существование в массиве
     * @param  string $key Пользовательский ключ
     * @return boolean true - существует | false - не существует
     */
    private function key_exists (string $key) {
        $data = $this->read();
        array_key_exists($key, $data);
        return;
    }
}
