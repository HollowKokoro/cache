<?php 
declare(strict_types=1);

class Memory implements CacheInterface
{
    /**
     * @var array $data Массив с данными
     */
    private array $data;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, $ttl): void
    {
        $this->data[$key] = $value;
        $this->data[$ttl] = $ttl;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key, $ttl): ValueInterface
    {
        if (array_key_exists($key, $this->data)) {
            $this->expiration($key, $ttl);
            return new ValueFound($this->data);
        } else{
            return new ValueNotFound;
        }
    }

    /**
     * {@inheritdoc}
     */    
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    private function expiration(string $key,  string $ttl): void
    {
        if (time() < time() + $this->data[$ttl]) {
            unset($this->data[$key]);
        }
    }
}