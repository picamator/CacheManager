<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Data;

use Picamator\CacheManager\Api\Data\SearchResultFactoryInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Api\ObjectManagerInterface;

/**
 * Factory to build SearchResult's objects
 */
class SearchResultFactory implements SearchResultFactoryInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var string
     */
    private $className;

    /**
     * @param ObjectManagerInterface    $objectManager
     * @param string                    $className
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        string $className = '\Picamator\CacheManager\Data\SearchResult'
    ) {
        $this->objectManager = $objectManager;
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, array $missedData) : SearchResultInterface
    {
        return $this->objectManager->create($this->className, [$data, $missedData]);
    }
}
