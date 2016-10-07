<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Spi\Data\EventInterface;

/**
 * Event data interface
 *
 * It's send within rising event
 *
 * @codeCoverageIgnore
 */
class Event implements EventInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var SearchCriteriaInterface
     */
    private $searchCriteria;

    /**
     * @var array
     */
    private $argumentList;

    /**
     * @var null|SearchResultInterface|\stdClass
     */
    private $operationResult;

    /**
     * @param string                                $name
     * @param SearchCriteriaInterface               $searchCriteria
     * @param array                                 $argumentList
     * @param null|\stdClass|SearchResultInterface   $operationResult
     */
    public function __construct(
        string $name,
        SearchCriteriaInterface $searchCriteria,
        array $argumentList,
        $operationResult
    ) {
        $this->name             = $name;
        $this->searchCriteria   = $searchCriteria;
        $this->argumentList     = $argumentList;
        $this->operationResult  = $operationResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchCriteria() : SearchCriteriaInterface
    {
        return $this->searchCriteria;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgumentList() : array
    {
        return $this->argumentList;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationResult()
    {
        return $this->operationResult;
    }

    /**
     * @see http://php.net/manual/en/language.oop5.magic.php#object.debuginfo
     */
    public function __debugInfo()
    {
        return [
            'name'              => $this->name,
            'searchCriteria'    => $this->searchCriteria,
            'argumentList'      => $this->argumentList,
            'operationResult'   => $this->operationResult,
        ];
    }
}
