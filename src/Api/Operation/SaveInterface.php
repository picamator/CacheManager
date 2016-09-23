<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\SearchCriteriaInterface;
use Picamator\CacheManager\InvalidArgumentException;

/**
 * Save operation
 */
interface SaveInterface
{
	/**
	 * Save data to Cache
	 *
	 * @param SearchCriteriaInterface	$searchCriteria
	 * @param array 					$data collection of entities
	 * 
	 * @return bool 'true' for success save and 'false' otherwise
	 * 
	 * @throws InvalidArgumentException
	 */
	public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool;
}
