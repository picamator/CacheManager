<?php
namespace Picamator\CacheManager\Api\Data;

/**
 * Abstraction over search query
 */
interface SearchCriteriaInterface 
{
	/**
	 * Retrieve context name
	 * 
	 * @return string
	 */
	public function getContextName() : string;
	
	/**
	 * Retrieve entity name
	 * 
	 * @return string
	 */
	public function getEntityName() : string;
	
	/**
	 * Retrieve id's list
	 * 
	 * @return array
	 */
	public function getIdList() : array;
	
	/**
	 * Retrieve field's list
	 * 
	 * @return array
	 */
	public function getFieldList() : array;

    /**
     * Retrieve identifier name
     *
     * @return string
     */
    public function getIdName() : string;
}
