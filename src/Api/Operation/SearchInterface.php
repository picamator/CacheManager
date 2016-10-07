<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Exception\InvalidCacheKeyException;

/**
 * Search operation
 */
interface SearchInterface
{
	/**
	 * Search data in Cache with comparison requested $searchCriteria->getFieldList()
	 * 
	 * @param SearchCriteriaInterface $searchCriteria
	 * 
	 * @return SearchResultInterface
     *
     * @throws InvalidCacheKeyException
	 */
	public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface;
}
