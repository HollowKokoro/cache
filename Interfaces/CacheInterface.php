<?php

interface CacheInterface
{
    /**
     * Сохраняет значение по ключу
     * @param  string $key Имя ключа
     * @param  mixed $value Пользовательские данные
     * @return 
     */
    public function set($key, $value): void;

    /**
     * Возвращает значение по ключу или null если не существует значение по ключу
     * @param  string $key Имя ключа
     * @return mixed
     */
    public function get($key);
    
    /**
     * Удаляет значение по ключу
     * @param  string $key Имя ключа
     */
    public function remove($key): void;
}
