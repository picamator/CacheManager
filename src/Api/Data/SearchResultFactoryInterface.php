<?php
namespace Picamator\CacheManager\Api\Data;

/**
 * Factory to build SearchResult's objects
 */
interface SearchResultFactoryInterface
{
    /**
     * Create
     *
     * @param array $data
     * @param array $missedData
     *
     * @return SearchResultInterface
     */
    public function create(array $data, array $missedData) : SearchResultInterface;
}
