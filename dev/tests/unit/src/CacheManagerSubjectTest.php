<?php
namespace Picamator\CacheManager\Tests\Unit;

use Picamator\CacheManager\CacheManagerSubject;

class CacheManagerSubjectTest extends BaseTest
{
    /**
     * @var CacheManagerSubject
     */
    private $cacheManagerSubject;

    /**
     * @var \Picamator\CacheManager\Spi\Data\EventBuilderInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventBuilderMock;

    /**
     * @var \Picamator\CacheManager\Api\CacheManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheManagerMock;

    /**
     * @var \Picamator\CacheManager\Api\Data\SearchCriteriaInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaMock;

    /**
     * @var \Picamator\CacheManager\Spi\ObserverInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $observerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->cacheManagerMock = $this->getMockBuilder('Picamator\CacheManager\Api\CacheManagerInterface')
            ->getMock();

        $this->eventBuilderMock = $this->getMockBuilder('Picamator\CacheManager\Spi\Data\EventBuilderInterface')
            ->getMock();

        $this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
            ->getMock();

        $this->observerMock = $this->getMockBuilder('Picamator\CacheManager\Spi\ObserverInterface')
            ->getMock();

        $this->cacheManagerSubject = new CacheManagerSubject($this->cacheManagerMock, $this->eventBuilderMock);
    }

    public function testSave()
    {
        $data = [];

        // event mock
        $event = $this->getMockBuilder('Picamator\CacheManager\Spi\Data\EventInterface')
            ->getMock();

        $event->expects($this->exactly(2))
            ->method('getName')
            ->willReturnOnConsecutiveCalls('beforeSave', 'afterSave');

        // event builder mock
        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setName')
            ->withConsecutive(
                [$this->equalTo('beforeSave')],
                [$this->equalTo('afterSave')]
            )->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setSearchCriteria')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setArgumentList')
            ->with($this->equalTo([$data]))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->once())
            ->method('setOperationResult')
            ->with($this->equalTo(true))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('build')
            ->willReturn($event);

        // cache manager mock
        $this->cacheManagerMock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        // observer mock
        $this->observerMock->expects($this->exactly(2))
            ->method('update');

        // observers
        $this->cacheManagerSubject->attach('beforeSave', $this->observerMock);
        $this->cacheManagerSubject->attach('afterSave', $this->observerMock);

        $this->cacheManagerSubject->save($this->searchCriteriaMock, $data);
    }

    public function testSearch()
    {
        // operation result mock
        $searchResultMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchResultInterface')
            ->getMock();

        // event mock
        $event = $this->getMockBuilder('Picamator\CacheManager\Spi\Data\EventInterface')
            ->getMock();

        $event->expects($this->exactly(2))
            ->method('getName')
            ->willReturnOnConsecutiveCalls('beforeSearch', 'afterSearch');

        // event builder mock
        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setName')
            ->withConsecutive(
                [$this->equalTo('beforeSearch')],
                [$this->equalTo('afterSearch')]
            )->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setSearchCriteria')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->once())
            ->method('setOperationResult')
            ->with($this->equalTo($searchResultMock))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('build')
            ->willReturn($event);

        // cache manager mock
        $this->cacheManagerMock->expects($this->once())
            ->method('search')
            ->willReturn($searchResultMock);

        // observer mock
        $this->observerMock->expects($this->exactly(2))
            ->method('update');

        // observers
        $this->cacheManagerSubject->attach('beforeSearch', $this->observerMock);
        $this->cacheManagerSubject->attach('afterSearch', $this->observerMock);

        $this->cacheManagerSubject->search($this->searchCriteriaMock);
    }

    public function testDelete()
    {
        // event mock
        $event = $this->getMockBuilder('Picamator\CacheManager\Spi\Data\EventInterface')
            ->getMock();

        $event->expects($this->exactly(2))
            ->method('getName')
            ->willReturnOnConsecutiveCalls('beforeDelete', 'afterDelete');

        // event builder mock
        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setName')
            ->withConsecutive(
                [$this->equalTo('beforeDelete')],
                [$this->equalTo('afterDelete')]
            )->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('setSearchCriteria')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->once())
            ->method('setOperationResult')
            ->with($this->equalTo(true))
            ->willReturnSelf();

        $this->eventBuilderMock->expects($this->exactly(2))
            ->method('build')
            ->willReturn($event);

        // cache manager mock
        $this->cacheManagerMock->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        // observer mock
        $this->observerMock->expects($this->exactly(2))
            ->method('update');

        // observers
        $this->cacheManagerSubject->attach('beforeDelete', $this->observerMock);
        $this->cacheManagerSubject->attach('afterDelete', $this->observerMock);

        $this->cacheManagerSubject->delete($this->searchCriteriaMock);
    }

    public function testNotify()
    {
        $name = 'test';

        // observer mock
        $this->observerMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->cacheManagerSubject));

        // event mock
        $event = $this->getMockBuilder('Picamator\CacheManager\Spi\Data\EventInterface')
            ->getMock();

        $event->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        $this->cacheManagerSubject->attach($name, $this->observerMock);
        $this->cacheManagerSubject->notify($event);
    }

    public function testDetach()
    {
        $name = 'test';

        // event mock
        $event = $this->getMockBuilder('Picamator\CacheManager\Spi\Data\EventInterface')
            ->getMock();

        $event->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        // never
        $this->observerMock->expects($this->never())
            ->method('update');

        $this->cacheManagerSubject->attach($name, $this->observerMock);
        $this->cacheManagerSubject->detach($name, $this->observerMock);

        $this->cacheManagerSubject->notify($event);
    }
}
