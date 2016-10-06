<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Cache;

use Picamator\CacheManager\Api\Cache\KeyGeneratorInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Create cache key based on SearchCriteriaInterface
 */
class KeyGenerator implements KeyGeneratorInterface
{
    /**
     * @todo use private constant in php 7.1
     * @var string
     */
    private static $keySeparator = '_' ;

    /**
     * {@inheritdoc}
     */
    public function generate(int $id, SearchCriteriaInterface $searchCriteria) : string
    {
        $data = [
            $searchCriteria->getContextName(),
            $searchCriteria->getEntityName(),
            $id
        ];
        $data = array_filter($data);
        $result = implode(self::$keySeparator, $data);
        
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function generateList(SearchCriteriaInterface $searchCriteria) : array
    {
        $result = [];
        foreach ($searchCriteria->getIdList() as $item) {
            $result[] = $this->generate($item, $searchCriteria);
        }

        return $result;
    }
}
