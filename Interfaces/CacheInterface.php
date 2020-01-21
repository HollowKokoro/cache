<?php

interface CacheInterface
{
    /**
     * Сохраняет значение по ключу
     * @param  mixed $value Пользовательские данные
     */
    public function set(string $key, $value): void;

    /**
     * Возвращает значение по ключу или null если не существует значение по ключу
     * @param  $key Имя ключа
     * @return mixed
     */
    public function get(string $key);
    
    /**
     * Удаляет значение по ключу
     * @param  $key Имя ключа
     */
    public function remove(string $key): void;
}
