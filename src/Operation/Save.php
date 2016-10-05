<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Operation;

use Picamator\CacheManager\Api\Cache\CacheItemFactoryInterface;
use Picamator\CacheManager\Api\Cache\KeyGeneratorInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Operation\SaveInterface;
use Picamator\CacheManager\Exception\InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Save operation
 */
class Save implements SaveInterface
{
    /**
     * @var KeyGeneratorInterface
     */
    private $keyGenerator;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @var CacheItemFactoryInterface
     */
    private $cacheItemFactory;

    /**
     * @param KeyGeneratorInterface     $keyGenerator
     * @param CacheItemPoolInterface    $cacheItemPool
     * @param CacheItemFactoryInterface $cacheItemFactory
     */
    public function __construct(
        KeyGeneratorInterface $keyGenerator,
        CacheItemPoolInterface $cacheItemPool,
        CacheItemFactoryInterface $cacheItemFactory
    ) {
        $this->keyGenerator = $keyGenerator;
        $this->cacheItemPool = $cacheItemPool;
        $this->cacheItemFactory = $cacheItemFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool
    {
        // validate
        $idName = $searchCriteria->getIdName();
        if (!$this->isValid($idName, $data)) {
            throw new InvalidArgumentException(
                sprintf('Invalid data "%s". Data should be array of entities with identifier "%s".', serialize($data), $idName)
            );
        }

        // save
        foreach($data as $item) {
            $cacheKey = $this->keyGenerator->generate($item[$idName], $searchCriteria);
            $cacheItem = $this->cacheItemFactory->create($cacheKey, $item);

            $this->cacheItemPool->saveDeferred($cacheItem);
        }

        return $this->cacheItemPool->commit();
    }

    /**
     * Validate data structure: array of arrays with presented correct id
     *
     * @param string    $idName
     * @param array     $data
     *
     * @return bool _true_ for valid and _false_ otherwise
     *
     * @throws InvalidArgumentException
     */
    private function isValid(string $idName, array $data)
    {
        foreach ($data as $item) {
            if (!is_array($item) || !array_key_exists($idName, $item)) {
                return false;
            }
        }

        return true;
    }
}
