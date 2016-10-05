<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaBuilderInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\ObjectManagerInterface;

/**
 * Building search criteria object
 *
 * @codeCoverageIgnore
 */
class SearchCriteriaBuilder implements SearchCriteriaBuilderInterface
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
        'entityName'    => null,
        'idList'        => [],
        'fieldList'     => [],
        'idName'        => null,
        'contextName'   => null,
    ];

    /**
     * @param ObjectManagerInterface    $objectManager
     * @param string                    $className
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        string $className = 'Picamator\CacheManager\Data\SearchCriteria'
    ) {
       $this->objectManager = $objectManager;
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function setContextName(string $contextName)
    {
        $this->data['contextName'] = $contextName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityName(string $entityName)
    {
        $this->data['entityName'] = $entityName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdList(array $idList)
    {
        $this->data['idList'] = $idList;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFieldList(array $fieldList)
    {
        $this->data['fieldList'] = $fieldList;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdName(string $idName)
    {
        $this->data['idName'] = $idName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build() : SearchCriteriaInterface
    {
        /** @var SearchCriteriaInterface $result */
        $result = $this->objectManager->create($this->className, $this->data);
        $this->cleanData();

        return $result;
    }

    /**
     * Clean data
     */
    private function cleanData()
    {
        foreach ($this->data as &$item) {
            $item = is_array($item) ? [] : null;
        }
        unset($item); // prevent unexpected behaviour
    }
}
