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
     * @todo use private constant in php 7.1
     * @var string
     */
    private static $objectName = '\Picamator\CacheManager\Data\SearchResult';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, array $missedData) : SearchResultInterface
    {
        return $this->objectManager->create(self::$objectName, [$data, $missedData]);
    }
}
