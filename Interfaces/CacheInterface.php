<?php
declare(strict_types=1);

interface CacheInterface
{
    /**
     * Сохраняет значение по ключу
     * @param string $key Ключ массива
     * @param  mixed $value Пользовательские данные
     */
    public function set(string $key, $value): void;

    /**
     * Возвращает значение по ключу или null если не существует значение по ключу
     * @param string $key Ключ массива
     * @return mixed Возвращает содержимое массива по ключу
     
    public function get(string $key);*/
    
    /**
     * Удаляет значение по ключу
     * @param string $key Ключ массива
     
    public function remove(string $key): void;*/
}