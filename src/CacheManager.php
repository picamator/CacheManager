<?php
declare(strict_types = 1);

namespace Picamator\CacheManager;

use Picamator\CacheManager\Api\CacheManagerInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

use Picamator\CacheManager\Api\Operation\SaveInterface;
use \Picamator\CacheManager\Api\Operation\SearchInterface;
use \Picamator\CacheManager\Api\Operation\InvalidateInterface;

/**
 * Facade over operations: _save_, _search_, and _invalidate_
 * It's better to use Proxy over operations via DI for performance boost
 */
class CacheManager implements CacheManagerInterface
{
    /**
     * @var \Picamator\CacheManager\Api\Operation\SaveInterface
     */
    private $operationSave;

    /**
     * @var \Picamator\CacheManager\Api\Operation\SearchInterface
     */
    private $operationSearch;

    /**
     * @var \Picamator\CacheManager\Api\Operation\InvalidateInterface
     */
    private $operationInvalidate;

    /**
     * @param SaveInterface         $operationSave
     * @param SearchInterface       $operationSearch
     * @param InvalidateInterface   $operationInvalidate
     */
    public function __construct(
        SaveInterface $operationSave,
        SearchInterface $operationSearch,
        InvalidateInterface $operationInvalidate
    ) {
        $this->operationSave = $operationSave;
        $this->operationSearch = $operationSearch;
        $this->operationInvalidate = $operationInvalidate;
    }

    /**
     * {@inheritdoc}
     */
    public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool
    {
        return $this->operationSave->save($searchCriteria, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface
    {
        return $this->operationSearch->search($searchCriteria);
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate(SearchCriteriaInterface $searchCriteria)
    {
        return $this->operationInvalidate->invalidate($searchCriteria);
    }
}
