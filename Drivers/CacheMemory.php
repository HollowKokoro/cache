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
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key): ValueInterface
    {
        if (array_key_exists($key, $this->data)) {
            return new ValueFound($this->data);
        }
    }

    /**
     * {@inheritdoc}
     */    
    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }
}