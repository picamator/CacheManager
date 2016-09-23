<?php
namespace Picamator\CacheManager\Api\Builder;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Build cache key based on SearchCriteriaInterface
 */
interface CacheKeyInterface
{
    /**
     * Build
     *
     * @param int $id
     * @param SearchCriteriaInterface $searchCriteria
     * @return string
     */
    public function build(int $id, SearchCriteriaInterface $searchCriteria) : string;
}
