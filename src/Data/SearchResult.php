<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Data;

use Picamator\CacheManager\Api\Data\SearchResultInterface;

/**
 * Wrapper over search result, represents what data was getting from cache as well as what should be ask from API
 */
class SearchResult implements SearchResultInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $missedData;

    /**
     * @var bool
     */
    private $hasData;

    /**
     * @var int
     */
    private $count;

    /**
     * @param array $data
     * @param array $missedData
     */
    public function __construct(array $data, array $missedData)
    {
        $this->data = $data;
        $this->missedData = $missedData;
    }

    /**
     * {@inheritdoc}
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getMissedData() : array
    {
        return $this->missedData;
    }

    /**
     * {@inheritdoc}
     */
    public function hasData() : bool
    {
        if (is_null($this->hasData)) {
            $this->hasData = count($this->data) > 0;
        }

        return $this->hasData;
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        if (is_null($this->count)) {
            $this->count = count($this->data);
        }

        return $this->count;
    }
}
