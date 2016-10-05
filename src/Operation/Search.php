<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Operation;

use Picamator\CacheManager\Api\Cache\KeyGeneratorInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultFactoryInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Api\Operation\SearchInterface;
use Picamator\CacheManager\Exception\InvalidCacheKeyException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;

/**
 * Search operation
 */
class Search implements SearchInterface
{
    /**
     * @var KeyGenerator
     */
    private $keyGenerator;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @var SearchResultFactoryInterface
     */
    private $searchResultFactory;

    /**
     * @param KeyGeneratorInterface         $keyGenerator
     * @param CacheItemPoolInterface        $cacheItemPool
     * @param SearchResultFactoryInterface  $searchResultFactory
     */
    public function __construct(
        KeyGeneratorInterface $keyGenerator,
        CacheItemPoolInterface $cacheItemPool,
        SearchResultFactoryInterface $searchResultFactory
    ) {
        $this->keyGenerator = $keyGenerator;
        $this->cacheItemPool = $cacheItemPool;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface
    {
        $cacheItemGenerator = $this->getCacheItemGenerator($searchCriteria);
        $fieldList = $searchCriteria->getFieldList();

        $data = [];
        $missedData = [];

        /** @var \Psr\Cache\CacheItemInterface $value */
        foreach ($cacheItemGenerator as $key => $value) {
            $itemData   = $value->get();
            $fieldDiff  = array_diff($fieldList, array_keys($itemData));
            if (!is_null($itemData) && !$fieldDiff) {
                $data[] = $value;
                continue;
            }

            $missedData[] = $key;
        }

        return $this->searchResultFactory->create($data, $missedData);
    }

    /**
     * Retrieve cache data generator
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return void
     *
     * @throws InvalidCacheKeyException
     */
    private function getCacheItemGenerator(SearchCriteriaInterface $searchCriteria)
    {
        try {
            foreach ($searchCriteria->getIdList() as $item) {
                $cacheKey = $this->keyGenerator->generate($item, $searchCriteria);

                yield $item => $this->cacheItemPool->getItem($cacheKey);
            }
        } catch (PsrCacheInvalidArgumentException $e) {
            throw new InvalidCacheKeyException($e->getMessage(), $e->getCode());
        }
    }
}
