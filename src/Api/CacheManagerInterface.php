<?php
namespace Picamator\CacheManager\Api;

use Picamator\CacheManager\Api\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\ContainerInterface;

/**
 * Facade over operations: _save_, _search_, and _invalidate_
 */
interface CacheManagerInterface 
{
	/**
	 * Save data to Cache
	 * 
	 * @param SearchCriteriaInterface 	$searchCriteria
	 * @param array 					$data
	 * 
	 * @return bool _true_ for success save or _false_ otherwise
	 */
	public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool;
	
	/**
	 * Search data from Cache
	 * 
	 * @param SearchCriteriaInterface $searchCriteria
	 * 
	 * @return ContainerInterface
	 */
	public function search(SearchCriteriaInterface $searchCriteria) : ContainerInterface;
	
	/**
	 * Invlidate Cache
	 * 
	 * @param SearchCriteriaInterface 	$searchCriteria
	 * @param array 					$data
	 * 
	 * @return bool
	 */
	public function invalidate(SearchCriteriaInterface $searchCriteria) : bool;
}
