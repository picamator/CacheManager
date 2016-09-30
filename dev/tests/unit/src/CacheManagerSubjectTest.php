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
     * @var \Picamator\CacheManager\Api\CacheManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheManagerMock;

    /**
     * @var \Picamator\CacheManager\CacheManagerSubject | \PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheManagerSubjectMock;

    /**
     * @var \Picamator\CacheManager\Api\Data\SearchCriteriaInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaMock;

    protected function setUp()
    {
        parent::setUp();

        $this->cacheManagerMock = $this->getMockBuilder('Picamator\CacheManager\Api\CacheManagerInterface')
            ->getMock();

        $this->cacheManagerSubjectMock = $this->getMockBuilder('Picamator\CacheManager\CacheManagerSubject')
            ->setConstructorArgs([$this->cacheManagerMock])
            ->setMethodsExcept(['save', 'search', 'invalidate'])
            ->getMock();

        $this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
            ->getMock();

        $this->cacheManagerSubject = new CacheManagerSubject($this->cacheManagerMock);
    }

    public function testSave()
    {
        // cache manager subject mock
        $this->cacheManagerSubjectMock->expects($this->exactly(2))
            ->method('notify')
            ->withConsecutive(
                [$this->equalTo('beforeSave')],
                [$this->equalTo('afterSave')]
            );

        // cache manager mock
        $this->cacheManagerMock->expects($this->once())
            ->method('save');

        $this->cacheManagerSubjectMock->save($this->searchCriteriaMock, []);
    }

    public function testSearch()
    {
        // cache manager subject mock
        $this->cacheManagerSubjectMock->expects($this->exactly(2))
            ->method('notify')
            ->withConsecutive(
                [$this->equalTo('beforeSearch')],
                [$this->equalTo('afterSearch')]
            );

        // cache manager mock
        $this->cacheManagerMock->expects($this->once())
            ->method('search');

        $this->cacheManagerSubjectMock->search($this->searchCriteriaMock);
    }

    public function testInvalidate()
    {
        // cache manager subject mock
        $this->cacheManagerSubjectMock->expects($this->exactly(2))
            ->method('notify')
            ->withConsecutive(
                [$this->equalTo('beforeInvalidate')],
                [$this->equalTo('afterInvalidate')]
            );

        // cache manager mock
        $this->cacheManagerMock->expects($this->once())
            ->method('invalidate');

        $this->cacheManagerSubjectMock->invalidate($this->searchCriteriaMock);
    }

    public function testNotify()
    {
        $event = 'test';

        // observer mock
        $observerMock = $this->getMockBuilder('Picamator\CacheManager\Spi\ObserverInterface')
            ->getMock();

        $observerMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->cacheManagerSubject));

        $this->cacheManagerSubject->attach($event, $observerMock);
        $this->cacheManagerSubject->notify($event);
    }
}
