<?php 
declare(strict_types=1);

class Memory implements CacheInterface
{
    /**
     * @param  $data Массив с данными
     */
    private array $data;

    /**
     * Конструктор
     * @param  $data Массив с пользовательскими данными
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
    public function get(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return null;
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