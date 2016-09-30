<?php
declare(strict_types = 1);

namespace Picamator\CacheManager;

use Picamator\CacheManager\Api\CacheManagerInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Spi\ObserverInterface;
use Picamator\CacheManager\Spi\SubjectInterface;

/**
 * Subject as a part observer pattern implementation
 */
class CacheManagerSubject implements CacheManagerInterface, SubjectInterface
{
    /**
     * @var CacheManagerInterface
     */
    private $cacheManager;

    /**
     * @var array
     */
    private $observerContainer = [];

    /**
     * @param CacheManagerInterface $cacheManager
     */
    public function __construct(CacheManagerInterface $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * {@inheritdoc}
     *
     * Events: beforeSave, afterSave
     */
    public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool
    {
        $this->notify('beforeSave');
        $result = $this->cacheManager->save($searchCriteria, $data);
        $this->notify('afterSave');

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     *  Events: beforeSearch, afterSearch
     */
    public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface
    {
        $this->notify('beforeSearch');
        $result = $this->cacheManager->search($searchCriteria);
        $this->notify('afterSearch');

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * Events: beforeInvalidate, afterInvalidate
     */
    public function invalidate(SearchCriteriaInterface $searchCriteria)
    {
        $this->notify('beforeInvalidate');
        $result = $this->cacheManager->invalidate($searchCriteria);
        $this->notify('afterInvalidate');

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(string $event, ObserverInterface $observer)
    {
        $observerList = $this->getObserverList($event);
        $observerList->attach($observer);
    }

    /**
     * {@inheritdoc}
     */
    public function detach(string $event, ObserverInterface $observer)
    {
        $observerList = $this->getObserverList($event);
        $observerList->detach($observer);
    }

    /**
     * {@inheritdoc}
     */
    public function notify(string $event)
    {
        $observerList = $this->getObserverList($event);
        /** @var \Picamator\CacheManager\Spi\ObserverInterface $item */
        foreach($observerList as $item) {
            $item->update($this);
        }
    }

    /**
     * Retrieve observer list
     *
     * @param string $event
     *
     * @return \SplObjectStorage
     */
    private function getObserverList(string $event) : \SplObjectStorage
    {
        if (empty($this->observerContainer[$event])) {
            $this->observerContainer[$event] = new \SplObjectStorage();
        }

        return $this->observerContainer[$event];
    }
}
