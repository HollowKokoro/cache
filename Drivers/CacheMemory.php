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
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        if (!array_key_exists($key, $this->data)) {
            return new ValueNotFound;
        }
        $this->expiration($key);
        return new ValueFound($this->data);
    }

    /**
     * {@inheritdoc}
     */    
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

        public function setExpiration(string $key, int $expire)
    {
        $this->ttl[$key] = $expire;
    }

    /**
     * expiration Присвает время жизни ключа
     * @param string $key Ключ массива
     * @param int $ttlSeconds Время жизни кюча в секундах
     */
    private function expiration(string $key): void
    {
        $ttlMilliseconds = $this->ttl[$key] * 1000;
        if (time() < time() + $ttlMilliseconds) {
            unset($this->data[$key]);
        }
    }
}