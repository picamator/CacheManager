<?php

declare(strict_types=1);

namespace Picamator\CacheManager\Operation;

use Picamator\CacheManager\Api\Cache\KeyGeneratorInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Operation\DeleteInterface;
use Picamator\CacheManager\Exception\InvalidCacheKeyException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;

/**
 * Delete operation.
 */
class Delete implements DeleteInterface
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
     * @param KeyGeneratorInterface  $keyGenerator
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(
        KeyGeneratorInterface $keyGenerator,
        CacheItemPoolInterface $cacheItemPool
    ) {
        $this->keyGenerator = $keyGenerator;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(SearchCriteriaInterface $searchCriteria) : bool
    {
        $cacheKeyList = $this->keyGenerator->generateList($searchCriteria);
        try {
            $result = $this->cacheItemPool->deleteItems($cacheKeyList);
        } catch (PsrCacheInvalidArgumentException $e) {
            throw new InvalidCacheKeyException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }
}
