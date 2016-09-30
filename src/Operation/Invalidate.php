<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Operation;

use Picamator\CacheManager\Api\Cache\KeyGeneratorInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Operation\InvalidateInterface;
use Picamator\CacheManager\Exception\InvalidCacheKeyException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;

/**
 * Invalidate operation
 */
class Invalidate implements InvalidateInterface
{
    /**
     * @var KeyGeneratorInterface
     */
    private $keyGenerator;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @var \DateTime
     */
    private $currentTime;

    /**
     * @param KeyGeneratorInterface     $keyGenerator
     * @param CacheItemPoolInterface    $cacheItemPool
     */
    public function __construct(
        KeyGeneratorInterface $keyGenerator,
        CacheItemPoolInterface $cacheItemPool
    ) {
        $this->keyGenerator = $keyGenerator;
        $this->cacheItemPool = $cacheItemPool;

        $this->currentTime = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate(SearchCriteriaInterface $searchCriteria)
    {
        $cacheKeyList = $this->keyGenerator->generateList($searchCriteria);
        try {
            $cacheItemList = $this->cacheItemPool->getItems($cacheKeyList);
        } catch (PsrCacheInvalidArgumentException $e) {
            throw new InvalidCacheKeyException($e->getMessage(), $e->getCode());
        }

        /** @var \Psr\Cache\CacheItemInterface $item */
        foreach ($cacheItemList as $item) {
            $item->expiresAt($this->currentTime);
        }
    }
}
