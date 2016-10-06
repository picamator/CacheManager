<?php
namespace Picamator\CacheManager\Spi\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Exception\InvalidArgumentException;

/**
 * Building Event's objects
 */
interface EventBuilderInterface
{
    /**
     * Sets name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name) : self;

    /**
     * Sets search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return self
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria) : self;

    /**
     * Sets argument list
     *
     * It's all arguments used to run operation except $searchCriteria
     *
     * @param array $argumentList
     *
     * @return self
     */
    public function setArgumentList(array $argumentList) : self;

    /**
     * Sets operation result
     *
     * Result of executing operation, it's relevant to all _after_ events only
     *
     * @param null|boolean|SearchResultInterface $operationResult
     *
     * @return EventBuilderInterface
     *
     * @throws self
     */
    public function setOperationResult($operationResult) : self;

    /**
     * Build
     *
     * It SHOULD clear all builder's data after creating Event.
     * It's essential for preparing builder to create new Event.
     *
     * @return EventInterface
     */
    public function build() : EventInterface;
}
