<?php
namespace Picamator\CacheManager\Test\Unit\Operation;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManager\Operation\Invalidate;
use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;

class InvalidateTest extends BaseTest
{
    /**
     * @var Invalidate
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

        $this->operation = new Invalidate(
            $this->keyGeneratorMock,
            $this->cacheItemPoolMock
        );
    }

    public function testInvalidate()
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
            ->willReturn($cacheKeyList);

        $this->operation->invalidate($this->searchCriteriaMock);
    }

    /**
     * @expectedException \Picamator\CacheManager\Exception\InvalidCacheKeyException
     */
    function testInvalidKeyCacheSearch()
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

        $this->operation->invalidate($this->searchCriteriaMock);
    }
}
