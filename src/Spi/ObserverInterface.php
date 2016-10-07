<?php
namespace Picamator\CacheManager\Spi;

use Picamator\CacheManager\Spi\Data\EventInterface;
use Picamator\CacheManager\Spi\SubjectInterface;

/**
 * Observer as a part observer pattern implementation
 */
interface ObserverInterface 
{
	/**
	 * Update
	 * 
	 * @param SubjectInterface  $subject
	 * @param EventInterface    $event
	 */
	public function update(SubjectInterface $subject, EventInterface $event);
}
