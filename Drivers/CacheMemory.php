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
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value): array
    {
        $this->data[$key] = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($key)
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
    public function remove($key)
    {
        unset($this->data[$key]);
    }
}