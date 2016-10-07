<?php
declare(strict_types = 1);

namespace Picamator\CacheManager;

use Picamator\CacheManager\Api\CacheManagerInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

use Picamator\CacheManager\Api\Operation\SaveInterface;
use Picamator\CacheManager\Api\Operation\SearchInterface;
use Picamator\CacheManager\Api\Operation\DeleteInterface;

/**
 * Facade over operations: _save_, _search_, and _invalidate_
 *
 * It's better to use Proxy over operations via DI for performance boost
 */
class CacheManager implements CacheManagerInterface
{
    /**
     * @var SaveInterface
     */
    private $operationSave;

    /**
     * @var SearchInterface
     */
    private $operationSearch;

    /**
     * @var DeleteInterface
     */
    private $operationDelete;

    /**
     * @param SaveInterface         $operationSave
     * @param SearchInterface       $operationSearch
     * @param DeleteInterface       $operationDelete
     */
    public function __construct(
        SaveInterface $operationSave,
        SearchInterface $operationSearch,
        DeleteInterface $operationDelete
    ) {
        $this->operationSave = $operationSave;
        $this->operationSearch = $operationSearch;
        $this->operationDelete = $operationDelete;
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
    public function delete(SearchCriteriaInterface $searchCriteria) : bool
    {
        return $this->operationDelete->delete($searchCriteria);
    }
}
