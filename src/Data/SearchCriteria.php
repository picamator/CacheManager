<?php
declare(strict_types = 1);

namespace Picamator\CacheManager\Data;

use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;

/**
 * Abstraction over search query
 *
 * @codeCoverageIgnore
 */
class SearchCriteria implements SearchCriteriaInterface
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var array
     */
    private $idList;

    /**
     * @var array
     */
    private $fieldList;

    /**
     * @var null|string
     */
    private $contextName;

    /**
     * @var string
     */
    private $idName;

    /**
     * @param string        $entityName
     * @param array         $idList
     * @param array         $fieldList
     * @param string        $idName
     * @param string|null   $contextName
     */
    public function __construct(
        string $entityName,
        array $idList,
        array $fieldList,
        string $idName,
        string $contextName = null
    ) {
        $this->entityName   = $entityName;
        $this->idList       = $idList;
        $this->fieldList    = $fieldList;
        $this->idName       = $idName;
        $this->contextName  = $contextName;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextName() : string
    {
        return $this->contextName;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityName() : string
    {
        return $this->entityName;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdList() : array
    {
        return $this->idList;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldList() : array
    {
        return $this->fieldList;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdName() : string
    {
        return $this->idName;
    }
}
