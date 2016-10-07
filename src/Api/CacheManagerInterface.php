<?php
namespace Picamator\CacheManager\Api;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Exception\InvalidCacheKeyException;

/**
 * Facade over operations: _save_, _search_, and _invalidate_
 *
 * It's better to use Proxy over operations via DI for performance boost
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
	 * @return SearchResultInterface
     *
     * @throws InvalidCacheKeyException
	 */
	public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface;
	
	/**
	 * Delete items from Cache
	 * 
	 * @param SearchCriteriaInterface 	$searchCriteria
     *
     * @return bool _true_ for success save or _false_ otherwise
     *
     * @throws InvalidCacheKeyException
	 */
	public function delete(SearchCriteriaInterface $searchCriteria) : bool ;
}
