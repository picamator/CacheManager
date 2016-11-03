<?php

declare(strict_types=1);

namespace Picamator\CacheManager\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\ObjectManagerInterface;
use Picamator\CacheManager\Exception\InvalidArgumentException;
use Picamator\CacheManager\Spi\Data\EventBuilderInterface;
use Picamator\CacheManager\Spi\Data\EventInterface;

/**
 * Building Event's objects.
 *
 * @codeCoverageIgnore
 */
class EventBuilder implements EventBuilderInterface
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
     * @var array
     */
    private $data = [
        'name'              => null,
        'searchCriteria'    => null,
        'argumentList'      => [],
        'operationResult'   => null,
    ];

    /**
     * @param ObjectManagerInterface $objectManager
     * @param string                 $className
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        string $className = 'Picamator\CacheManager\Data\Event'
    ) {
        $this->objectManager = $objectManager;
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name) : EventBuilderInterface
    {
        $this->data['name'] = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria) : EventBuilderInterface
    {
        $this->data['searchCriteria'] = $searchCriteria;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setArgumentList(array $argumentList) : EventBuilderInterface
    {
        $this->data['argumentList'] = $argumentList;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperationResult($operationResult) : EventBuilderInterface
    {
        if (!is_null($operationResult)
            && !is_bool($operationResult)
            && !is_a($operationResult, '\Picamator\CacheManager\Api\Data\SearchResultInterface')
        ) {
            throw new InvalidArgumentException('Invalid $operationResult type. It should be null, boolean or SearchResultInterface.');
        }

        // convert boolean to scalar object to prevent null-true-false problem
        if (is_bool($operationResult)) {
            $operationResult = (object) $operationResult;
        }

        $this->data['operationResult'] = $operationResult;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build() : EventInterface
    {
        /** @var EventInterface $result */
        $result = $this->objectManager->create($this->className, $this->data);
        $this->cleanData();

        return $result;
    }

    /**
     * Clean data.
     */
    private function cleanData()
    {
        foreach ($this->data as &$item) {
            $item = is_array($item) ? [] : null;
        }
        unset($item); // prevent unexpected behaviour
    }
}
