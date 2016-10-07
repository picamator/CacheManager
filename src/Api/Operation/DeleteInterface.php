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
     * @return bool _true_ for success save and _false_ otherwise
     *
     * @throws InvalidCacheKeyException
	 */
	public function delete(SearchCriteriaInterface $searchCriteria) : bool;
}
