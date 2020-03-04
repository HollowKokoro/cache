<?php 
declare(strict_types=1);

class Memory implements CacheInterface
{
    /**
     * @var array $data Массив с данными
     */
    private array $data;
    private array $ttl;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->data = [];
        $this->ttl = [];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, ?int $expire = null): void
    {
        $this->data[$key] = $value;
        $this->ttl[$key] = $expire;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        if (!array_key_exists($key, $this->data)) {
            return new ValueNotFound();
        }
        if (time() > $this->ttl[$key]) {
            return new ValueFound($this->data[$key]);
        }
        $this->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }
}