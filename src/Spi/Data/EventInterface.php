<?php
namespace Picamator\CacheManager\Spi\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;

/**
 * Event data interface
 *
 * It's send within rising event
 */
interface EventInterface
{
    /**
     * Retrieve event name
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Retrieve search criteria
     *
     * @return SearchCriteriaInterface
     */
    public function getSearchCriteria() : SearchCriteriaInterface;

    /**
     * Retrieve data by name
     *
     * @return array list of operation arguments except _$searchCriteria_ with keeping source order
     */
    public function getArgumentList() : array;

    /**
     * Retrieve operation execution result
     *
     * @return null|\stdClass|SearchResultInterface _null_ operation has not executed yet, _stdClass_ is object over boolean scalar
     */
    public function getOperationResult();
}
