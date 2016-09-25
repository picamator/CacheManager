<?php
namespace Picamator\CacheManager\Api\Cache;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Create cache key based on SearchCriteriaInterface
 */
interface KeyGeneratorInterface
{
    /**
     * Generate
     *
     * @param int $id
     * @param SearchCriteriaInterface $searchCriteria
     * @return string
     */
    public function generate(int $id, SearchCriteriaInterface $searchCriteria) : string;
}
