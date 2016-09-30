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
        $cacheItemList = $this->getCacheItemList($searchCriteria);
        $fieldList = $searchCriteria->getFieldList();

        $data = [];
        $missedData = [];

        /** @var \Psr\Cache\CacheItemInterface $value */
        foreach ($cacheItemList as $key => $value) {
            $itemData = $value->get();
            if (!is_null($itemData) && !array_diff($fieldList, array_keys($itemData))) {
                $data[] = $value;
                continue;
            }

            $missedData[] = $key;
        }


        return $this->searchResultFactory->create($data, $missedData);
    }

    /**
     * Retrieve cache data list
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return array it's keys are an entity identifier
     *
     * @throws InvalidCacheKeyException
     */
    private function getCacheItemList(SearchCriteriaInterface $searchCriteria) : array
    {
        $result = [];
        try {
            foreach ($searchCriteria->getIdList() as $item) {
                $cacheKey = $this->keyGenerator->generate($item, $searchCriteria);
                $result[$item] = $this->cacheItemPool->getItem($cacheKey);
            }
        } catch (PsrCacheInvalidArgumentException $e) {
            throw new InvalidCacheKeyException($e->getMessage(), $e->getCode());
        }

        return $result;
    }
}
