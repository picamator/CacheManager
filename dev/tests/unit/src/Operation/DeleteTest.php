<?php
namespace Picamator\CacheManager\Test\Unit\Operation;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManager\Operation\Delete;
use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;

class deleteTest extends BaseTest
{
    /**
     * @var Delete
     */
    private $operation;

    /**
     * @var \Picamator\CacheManager\Cache\KeyGenerator | \PHPUnit_Framework_MockObject_MockObject
     */
    private $keyGeneratorMock;

    /**
     * @var \Psr\Cache\CacheItemPoolInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheItemPoolMock;

    /**
     * @var \Picamator\CacheManager\Data\SearchCriteria | \PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaMock;

    protected function setUp()
    {
        parent::setUp();

        $this->keyGeneratorMock = $this->getMockBuilder('Picamator\CacheManager\Cache\KeyGenerator')
            ->getMock();

        $this->cacheItemPoolMock = $this->getMockBuilder('Psr\Cache\CacheItemPoolInterface')
            ->getMock();

        $this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Data\SearchCriteria')
            ->disableOriginalConstructor()
            ->getMock();

        $this->operation = new Delete(
            $this->keyGeneratorMock,
            $this->cacheItemPoolMock
        );
    }

    public function testDelete()
    {
        $cacheKeyList = [1 =>'internal_customer_1'];

        // cache generator mock
        $this->keyGeneratorMock->expects($this->once())
            ->method('generateList')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturn($cacheKeyList);

        // cache item pool mock
        $this->cacheItemPoolMock->expects($this->once())
            ->method('deleteItems')
            ->willReturn($cacheKeyList)
            ->willReturn(true);

        $actual = $this->operation->delete($this->searchCriteriaMock);
        $this->assertTrue($actual);
    }

    /**
     * @expectedException \Picamator\CacheManager\Exception\InvalidCacheKeyException
     */
    public function testDeleteKeyCacheSearch()
    {
        $cacheKeyList = [1 =>'internal_customer_1'];

        // cache generator mock
        $this->keyGeneratorMock->expects($this->once())
            ->method('generateList')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturn($cacheKeyList);

        // cache item pool mock
        $exception = new class extends \RuntimeException implements PsrCacheInvalidArgumentException {};

        $this->cacheItemPoolMock->expects($this->once())
            ->method('deleteItems')
            ->willThrowException($exception);

        $this->operation->delete($this->searchCriteriaMock);
    }
}
