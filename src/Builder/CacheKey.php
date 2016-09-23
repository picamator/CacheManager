<?php
namespace Picamator\CacheManager\Builder;

use Picamator\CacheManager\Api\Builder\CacheKeyInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Build cache key based on SearchCriteriaInterface
 */
class CacheKey implements CacheKeyInterface
{
    /**
     * @TODO replace to private constant after support php 7.1
     * @var string
     */
    private static $keySeparator = '_' ;

    /**
     * @var array
     */
    private $buildContainer = [];

    /**
     * {@inheritdoc}
     */
    public function build(int $id, SearchCriteriaInterface $searchCriteria) : string
    {
        if (empty($this->buildContainer[$id])) {
            $data = [
                $searchCriteria->getContextName(),
                $searchCriteria->getEntityName(),
                $id
            ];
            $data = array_filter($data);

            $this->buildContainer[$id] = implode(self::$keySeparator, $data);
        }

        return $this->buildContainer[$id];
    }
}
