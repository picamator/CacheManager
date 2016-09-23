<?php
namespace Picamator\CacheManager\Api\Data;

/**
 * Wrapper over search result, represents what data was getting from cache as well as what should be ask from API
 */
interface ContainerInterface 
{
	/**
	 * Retrieve data
	 * 
	 * @return array
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
	 * @return bool 'true' if data exist or 'false' otherwise
	 */
	public function hasData() : bool;
	
	/**
	 * Count data
	 * 
	 * @return int
	 */
	public function count() : int;
}
