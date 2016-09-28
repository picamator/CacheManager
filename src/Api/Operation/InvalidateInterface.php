<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Invalidate operation
 */
interface InvalidateInterface
{
	/**
	 * Invalidate Cache
	 * 
	 * @param SearchCriteriaInterface $searchCriteria
     *
     * @throws InvalidCacheKeyException
	 */
	public function invalidate(SearchCriteriaInterface $searchCriteria);
}
