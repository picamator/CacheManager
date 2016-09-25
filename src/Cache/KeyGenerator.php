<?php
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
     * @var array
     */
    private $generateContainer = [];

    /**
     * {@inheritdoc}
     */
    public function generate(int $id, SearchCriteriaInterface $searchCriteria) : string
    {
        if (empty($this->generateContainer[$id])) {
            $data = [
                $searchCriteria->getContextName(),
                $searchCriteria->getEntityName(),
                $id
            ];
            $data = array_filter($data);

            $this->generateContainer[$id] = implode(self::$keySeparator, $data);
        }

        return $this->generateContainer[$id];
    }
}
