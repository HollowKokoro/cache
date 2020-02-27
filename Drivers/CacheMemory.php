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
        $this->data[$key, $ttl] = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key, $ttl): ValueInterface
    {
        if (array_key_exists($key, $this->data)) {
            $this->expiretion($key, $ttl);
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

    private function expiretion(string $key,  string $tll): void
    {
        if (time() < time() + $tll) {
            unset($this->data[$key]);
        }
    }
}