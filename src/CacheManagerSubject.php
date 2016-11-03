<?php

declare(strict_types=1);

namespace Picamator\CacheManager;

use Picamator\CacheManager\Api\CacheManagerInterface;
use Picamator\CacheManager\Api\Data\SearchCriteriaInterface;
use Picamator\CacheManager\Api\Data\SearchResultInterface;
use Picamator\CacheManager\Spi\Data\EventBuilderInterface;
use Picamator\CacheManager\Spi\Data\EventInterface;
use Picamator\CacheManager\Spi\ObserverInterface;
use Picamator\CacheManager\Spi\SubjectInterface;

/**
 * Subject as a part observer pattern implementation.
 */
class CacheManagerSubject implements CacheManagerInterface, SubjectInterface
{
    /**
     * @var CacheManagerInterface
     */
    private $cacheManager;

    /**
     * @var EventFactoryInterface
     */
    private $eventBuilder;

    /**
     * @var array
     */
    private $observerContainer = [];

    /**
     * @param CacheManagerInterface $cacheManager
     * @param EventBuilderInterface $eventBuilder
     */
    public function __construct(
        CacheManagerInterface $cacheManager,
        EventBuilderInterface $eventBuilder
    ) {
        $this->cacheManager = $cacheManager;
        $this->eventBuilder = $eventBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * Events: beforeSave, afterSave
     */
    public function save(SearchCriteriaInterface $searchCriteria, array $data) : bool
    {
        // before save event
        $eventBeforeName = 'beforeSave';
        if ($this->hasObserver($eventBeforeName)) {
            $eventBefore = $this->eventBuilder
                ->setName($eventBeforeName)
                ->setSearchCriteria($searchCriteria)
                ->setArgumentList([$data])
                ->build();

            $this->notify($eventBefore);
        }

        // execute operation
        $result = $this->cacheManager->save($searchCriteria, $data);

        // after save event
        $eventAfterName = 'afterSave';
        if ($this->hasObserver($eventAfterName)) {
            $eventAfter = $this->eventBuilder
                ->setName($eventAfterName)
                ->setSearchCriteria($searchCriteria)
                ->setArgumentList([$data])
                ->setOperationResult($result)
                ->build();

            $this->notify($eventAfter);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * Events: beforeSearch, afterSearch
     */
    public function search(SearchCriteriaInterface $searchCriteria) : SearchResultInterface
    {
        // before search event
        $eventBeforeName = 'beforeSearch';
        if ($this->hasObserver($eventBeforeName)) {
            $eventBefore = $this->eventBuilder
                ->setName($eventBeforeName)
                ->setSearchCriteria($searchCriteria)
                ->build();

            $this->notify($eventBefore);
        }

        // execute operation
        $result = $this->cacheManager->search($searchCriteria);

        // after search event
        $eventAfterName = 'afterSearch';
        if ($this->hasObserver($eventAfterName)) {
            $eventAfter = $this->eventBuilder
                ->setName($eventAfterName)
                ->setSearchCriteria($searchCriteria)
                ->setOperationResult($result)
                ->build();

            $this->notify($eventAfter);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * Events: beforeDelete, afterDelete
     */
    public function delete(SearchCriteriaInterface $searchCriteria) : bool
    {
        // before delete event
        $eventBeforeName = 'beforeDelete';
        if ($this->hasObserver($eventBeforeName)) {
            $eventBefore = $this->eventBuilder
                ->setName($eventBeforeName)
                ->setSearchCriteria($searchCriteria)
                ->build();

            $this->notify($eventBefore);
        }

        // execute operation
        $result = $this->cacheManager->delete($searchCriteria);

        // after delete event
        $eventAfterName = 'afterDelete';
        if ($this->hasObserver($eventAfterName)) {
            $eventAfter = $this->eventBuilder
                ->setName($eventAfterName)
                ->setSearchCriteria($searchCriteria)
                ->setOperationResult($result)
                ->build();

            $this->notify($eventAfter);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(string $name, ObserverInterface $observer)
    {
        $observerList = $this->getObserverList($name);
        $observerList->attach($observer);
    }

    /**
     * {@inheritdoc}
     */
    public function detach(string $name, ObserverInterface $observer)
    {
        $observerList = $this->getObserverList($name);
        $observerList->detach($observer);
    }

    /**
     * {@inheritdoc}
     */
    public function notify(EventInterface $event)
    {
        $observerList = $this->getObserverList($event->getName());
        /** @var \Picamator\CacheManager\Spi\ObserverInterface $item */
        foreach ($observerList as $item) {
            $item->update($this, $event);
        }
    }

    /**
     * Retrieve observer list.
     *
     * @param string $name
     *
     * @return \SplObjectStorage
     */
    private function getObserverList(string $name) : \SplObjectStorage
    {
        if (empty($this->observerContainer[$name])) {
            $this->observerContainer[$name] = new \SplObjectStorage();
        }

        return $this->observerContainer[$name];
    }

    /**
     * Check whatever observer container has at least one observer.
     *
     * @param string $name
     *
     * @return bool _true_ observer container has at least one observer, _false_ otherwise
     */
    private function hasObserver(string $name) : bool
    {
        return !empty($this->observerContainer[$name]) && $this->observerContainer[$name]->count();
    }
}
