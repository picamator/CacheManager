<?php
namespace Picamator\CacheManager\Api\Operation;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Exception\InvalidArgumentException;

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
	 * @return bool _true_ for success save and _false_ otherwise
	 * 
	 * @throws InvalidArgumentException
	 */
	public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool;
}
