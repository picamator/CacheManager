<?php
namespace Picamator\CacheManager\Spi;

use Picamator\CacheManager\Spi\ObserverInterface;

/**
 * Subject as a part observer pattern implementation
 */
interface SubjectInterface 
{
	/**
	 * Attach
	 * 
	 * @param string 			$event
	 * @param ObserverInterface $observer
	 */
	public function attach(string $event, ObserverInterface $observer);
	
	/**
	 * Detach
	 * 
	 * @param string 			$event
	 * @param ObserverInterface $observer
	 */
	public function detach(string $event, ObserverInterface $observer);
	
	/**
	 * Notify
	 * 
	 * @param string $event
	 */
	public function notify(string $event);
}
