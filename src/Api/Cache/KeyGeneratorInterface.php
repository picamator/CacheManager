<?php
namespace Picamator\CacheManager\Api\Cache;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Create cache key based on SearchCriteriaInterface
 */
interface KeyGeneratorInterface
{
    /**
     * Generate cache key, $searchCriteria might not have id's e.g. during save
     *
     * @param int $id
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return string
     */
    public function generate(int $id, SearchCriteriaInterface $searchCriteria) : string;

    /**
     * Generate cache list
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return array each keys SHOULD be an entity identifier the value SHOULD be a cache key
     */
    public function generateList(SearchCriteriaInterface $searchCriteria) : array;
}
