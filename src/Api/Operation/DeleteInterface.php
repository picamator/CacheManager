<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Delete operation
 */
interface DeleteInterface
{
	/**
	 * Delete items from Cache
	 * 
	 * @param SearchCriteriaInterface $searchCriteria
     *
     * @throws InvalidCacheKeyException
	 */
	public function delete(SearchCriteriaInterface $searchCriteria);
}
