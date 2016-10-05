<?php
namespace Picamator\CacheManager\Api\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Building search criteria object
 */
interface SearchCriteriaBuilderInterface
{
    /**
     * Sets context name
     *
     * @param string $contextName
     *
     * @return self
     */
    public function setContextName(string $contextName);

    /**
     * Sets entity name
     *
     * @param string $entityName
     *
     * @return self
     */
    public function setEntityName(string $entityName);

    /**
     * Sets id's list
     *
     * @param array $idList
     *
     * @return self
     */
    public function setIdList(array $idList);

    /**
     * Sets field's list
     *
     * @param array $fieldList
     *
     * @return self
     */
    public function setFieldList(array $fieldList);

    /**
     * Sets identifier name
     *
     * @param string $idName
     *
     * @return self
     */
    public function setIdName(string $idName);

    /**
     * Build search criteria
     *
     * It SHOULD clear all builder's data after creating SearchCriteria.
     * It's essential for preparing builder to create new SearchCriteria.
     *
     * @return \Picamator\CacheManager\Api\Data\SearchCriteriaInterface
     */
    public function build() : SearchCriteriaInterface;
}
