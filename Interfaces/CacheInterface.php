<?php
declare(strict_types=1);

interface CacheInterface
{
    /**
     * Сохраняет значение по ключу
     * @param string $key Ключ массива
     * @param  scalar or compound $value Пользовательские данные
     */
    public function set(string $key, $value): void;

    /**
     * Возвращает значение по ключу или null если не существует значение по ключу
     * @param string $key Ключ массива
     * @return scalar or compound Возвращает содержимое массива по ключу
     */
    public function get(string $key): ValueInterface;
    
    /**
     * Удаляет значение по ключу
     * @param string $key Ключ массива
     */
    public function remove(string $key): void;
}