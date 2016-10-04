<?php
namespace Picamator\CacheManager\Cache;

use Picamator\CacheManager\Api\Cache\CacheItemFactoryInterface;
use Picamator\CacheManager\Api\ObjectManagerInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Factory to build CacheItem's objects
 */
class CacheItemFactory implements CacheItemFactoryInterface
{
    /**
     * @todo use private constant in php 7.1
     * @var string
     */
    private static $objectName = '\Cache\Adapter\Common\CacheItem';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $key, array $value) : CacheItemInterface
    {
        return $this->objectManager->create(self::$objectName, [$key, null, $value]);
    }
}
