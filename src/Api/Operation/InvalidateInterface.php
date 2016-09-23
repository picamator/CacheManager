<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\SearchCriteriaInterface;

/**
 * Invalidate operation
 */
interface InvalidateInterface
{
	/**
	 * Invlidate Cache
	 * 
	 * @param SearchCriteriaInterface $searchCriteria
	 * 
	 * @return bool
	 */
	public function invalidate(SearchCriteriaInterface $searchCriteria) : bool;
}