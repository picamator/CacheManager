<?php
namespace Picamator\CacheManager\Spi;

use Picamator\CacheManager\Spi\SubjectInterface;

/**
 * Observer as a part observer pattern implementation
 */
interface ObserverInterface 
{
	/**
	 * Update
	 * 
	 * @param SubjectInterface $subject
	 */
	public function update(SubjectInterface $subject);
}
