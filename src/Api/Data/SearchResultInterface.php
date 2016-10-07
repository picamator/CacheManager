<?php
namespace Picamator\CacheManager\Api\Data;

/**
 * Wrapper over search result, represents what data was getting from cache as well as what should be ask from API
 */
interface SearchResultInterface
{
	/**
	 * Retrieve data
	 * 
	 * @return array of \Psr\Cache\CacheItemInterface as elements
	 */
	public function getData() : array;
	
	/**
	 * Retrieve missed data
	 * In other words list of id's that were not found in cache
	 * 
	 * @return array
	 */
	public function getMissedData() : array;
	
	/**
	 * Check does container have any data
	 * 
	 * @return bool _true_ if data exist or _false_ otherwise
	 */
	public function hasData() : bool;
	
	/**
	 * Count data
	 * 
	 * @return int
	 */
	public function count() : int;
}
