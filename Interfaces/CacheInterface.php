<?php
declare(strict_types=1);

interface CacheInterface
{
    /**
     * Сохраняет значение по ключу
     * @param string $key Ключ массива
     * @param mixed $value Пользовательские данные
     * @param int $ttl Время жизни кюча в секундах
     */
    public function set(string $key, $value, ?int $ttl = null): void;

    /**
     * Возвращает значение по ключу или null если не существует значение по ключу
     * @param string $key Ключ массива
     * @return mixed Возвращает содержимое массива по ключу
     */
    public function get(string $key): ValueInterface;
    
    /**
     * Удаляет значение по ключу
     * @param string $key Ключ массива
     */
    public function remove(string $key): void;
}