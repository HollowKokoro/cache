<?php 
declare(strict_types=1);

class Memory implements CacheInterface
{
    /**
     * @param  $data - массив с данными
     */
    private array $data;

    /**
     * Конструктор
     * @param $path - нулевой массив
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
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