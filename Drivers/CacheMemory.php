<?php 
declare(strict_types=1);

class Memory implements CacheInterface
{
    /**
     * @var array $data Массив с данными
     */
    private array $data;
    private array $expiration;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->data = [];
        $this->expiration = [];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, ?int $ttl = null): void
    {
        $this->data[$key] = $value;
        $this->expiration[$key] = time() + $ttl;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        if (!array_key_exists($key, $this->data)) {
            return new ValueNotFound();
        }
        if (time() > $this->expiration[$key]) {
            $this->remove($key);
            return new ValueNotFound();
        }
        return new ValueFound($this->data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }
}