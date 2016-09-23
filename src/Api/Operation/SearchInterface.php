<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;

/**
 * Search operation
 */
interface SearchInterface
{
	/**
	 * Search data from Cache
	 * 
	 * @param SearchCriteriaInterface $searchCriteria
	 * 
	 * @return SearchResultInterface
	 */
	public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface;
}
