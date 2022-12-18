<?php

namespace Bot\Cache;

use Bot\Exceptions\CacheAdapterException;
use Memcached;

class CacheAdapter
{
    private Memcached $memcached;

    public function __construct()
    {
        $this->memcached = new Memcached();
        $this->memcached->addServer('localhost', CACHE_PORT)
            or throw new CacheAdapterException("Could not connect to cache");
    }

    public function cache(string $key, array $obj): bool
    {
        $success = $this->memcached->set($key, $obj, 90);

        error_log('>>> ' . var_export($this->memcached->getResultMessage(), true) . PHP_EOL);
        error_log("Failed to save data at the server: `$key: " . var_export($obj, true) . '`' . PHP_EOL);

        return $success;
    }

    public function restore(string $key): array|false
    {
        $result = $this->memcached->get($key);

        error_log('<<< ' . var_export($this->memcached->getResultMessage(), true) . PHP_EOL);

        return $result;
    }
}