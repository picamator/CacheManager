<?php
namespace Picamator\CacheManager\Api\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * Factory to build CacheItem's objects
 */
interface CacheItemFactoryInterface
{
    /**
     * Create
     *
     * @param string $key
     * @param array  $value
     *
     * @return CacheItemInterface
     */
    public function create(string $key, array $value) : CacheItemInterface;
}
