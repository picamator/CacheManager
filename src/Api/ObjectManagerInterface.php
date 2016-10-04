<?php
namespace Picamator\CacheManager\Api;

use Picamator\CacheManager\Exception\RuntimeException;

/**
 * Creates objects, the main usage inside factories
 * all objects are unshared, for shared objects please use DI service libraries
 */
interface ObjectManagerInterface
{
    /**
     * Create objects
     *
     * @param string $className
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public function create(string $className, array $arguments = []);
}