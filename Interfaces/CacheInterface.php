<?php

interface CacheInterface
{
    /**
     * Сохраняет значение по ключу
     * @param  string $key - имя ключа
     * @param  mixed $value - пользовательские данные
     * @return void
     */
    public function set($key, $value);

    /**
     * Возвращает значение по ключу или null если не существует значение по ключу
     * @param  string $key - имя ключа
     * @return mixed
     */
    public function get($key);
    
    /**
     * Удаляет значение по ключу
     * @param  string $key - имя ключа
     * @return void
     */
    public function remove($key);
}
