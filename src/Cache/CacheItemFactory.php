<?php

declare(strict_types=1);

namespace Picamator\CacheManager\Cache;

use Picamator\CacheManager\Api\Cache\CacheItemFactoryInterface;
use Picamator\CacheManager\Api\ObjectManagerInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Factory to build CacheItem's objects.
 */
class CacheItemFactory implements CacheItemFactoryInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var string
     */
    private $className;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param string                 $className
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        string $className = '\Cache\Adapter\Common\CacheItem'
    ) {
        $this->objectManager = $objectManager;
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $key, array $value) : CacheItemInterface
    {
        /** @var \Cache\Adapter\Common\CacheItem $cacheItem */
        $cacheItem = $this->objectManager->create($this->className, [$key])
            ->set($value);

        return $cacheItem;
    }
}
