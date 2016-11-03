<?php

namespace Picamator\CacheManager\Spi;

use Picamator\CacheManager\Spi\Data\EventInterface;

/**
 * Subject as a part observer pattern implementation.
 */
interface SubjectInterface
{
    /**
     * Attach.
     *
     * @param string            $name
     * @param ObserverInterface $observer
     */
    public function attach(string $name, ObserverInterface $observer);

    /**
     * Detach.
     *
     * @param string            $name
     * @param ObserverInterface $observer
     */
    public function detach(string $name, ObserverInterface $observer);

    /**
     * Notify.
     *
     * @param EventInterface $event
     */
    public function notify(EventInterface $event);
}
