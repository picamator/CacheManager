<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\ContainerInterface;

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
	 * @return ContainerInterface
	 */
	public function search(SearchCriteriaInterface $searchCriteria) : ContainerInterface;
}
