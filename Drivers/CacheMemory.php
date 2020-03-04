<?php 
declare(strict_types=1);

class CacheMemory implements CacheInterface
{
    /**
     * @var array $data Массив с данными
    */
    private array $data;
    
    /**
     * @var array $expiration Массив с временем жизни данных
    */
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
        if ($ttl !== null) {
            $this->expiration[$key] = time() + $ttl;
        } else {
            $this->expiration[$key] = INF;
        }
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