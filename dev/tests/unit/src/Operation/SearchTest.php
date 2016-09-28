<?php
namespace Picamator\CacheManager\Test\Unit\Operation;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManager\Operation\Search;
use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;

class SearchTest extends BaseTest
{
    /**
     * @var Search
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

    /**
     * @var \Picamator\CacheManager\Api\Data\SearchResultFactoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $searchResultFactoryMock;

    public function setUp()
    {
        parent::setUp();

        $this->keyGeneratorMock = $this->getMockBuilder('Picamator\CacheManager\Cache\KeyGenerator')
            ->getMock();

        $this->cacheItemPoolMock = $this->getMockBuilder('Psr\Cache\CacheItemPoolInterface')
            ->getMock();

        $this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Data\SearchCriteria')
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchResultFactoryMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchResultFactoryInterface')
            ->getMock();

        $this->operation = new Search(
            $this->keyGeneratorMock,
            $this->cacheItemPoolMock,
            $this->searchResultFactoryMock
        );
    }

    public function testHasInCacheSearch()
    {
        $cacheKey = 'internal_customer_1';
        $idName = 'id';
        $fieldList = ['id', 'name'];
        $id = 1;
        $data = [$idName => $id, 'name' => 'Sergii'];

        // cache generator mock
        $this->keyGeneratorMock->expects($this->once())
            ->method('generateList')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturn([$id => $cacheKey]);

        // search criteria mock
        $this->searchCriteriaMock->expects($this->once())
            ->method('getFieldList')
            ->willReturn($fieldList);

        // cache item mock
        $cacheItemMock = $this->getMockBuilder('Psr\Cache\CacheItemInterface')
            ->getMock();

        $cacheItemMock->expects($this->once())
            ->method('get')
            ->willReturn($data);

        // cache item pool mock
        $this->cacheItemPoolMock->expects($this->once())
            ->method('getItem')
            ->willReturn($cacheItemMock);

        // search result factory mock
        $searchResultMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchResultInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchResultFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo([$cacheItemMock]), $this->equalTo([]))
            ->willReturn($searchResultMock);

        $this->operation->search($this->searchCriteriaMock);
    }

    public function testHasNotInCacheSearch()
    {
        $cacheKey = 'internal_customer_1';
        $idName = 'id';
        $fieldList = ['id', 'name', 'address'];
        $id = 1;
        $data = [$idName => $id, 'name' => 'Sergii'];

        // cache generator mock
        $this->keyGeneratorMock->expects($this->once())
            ->method('generateList')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturn([$id => $cacheKey]);

        // search criteria mock
        $this->searchCriteriaMock->expects($this->once())
            ->method('getFieldList')
            ->willReturn($fieldList);

        // cache item mock
        $cacheItemMock = $this->getMockBuilder('Psr\Cache\CacheItemInterface')
            ->getMock();

        $cacheItemMock->expects($this->once())
            ->method('get')
            ->willReturn($data);

        // cache item pool mock
        $this->cacheItemPoolMock->expects($this->once())
            ->method('getItem')
            ->willReturn($cacheItemMock);

        // search result factory mock
        $searchResultMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchResultInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchResultFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo([]), $this->equalTo([$data[$idName]]))
            ->willReturn($searchResultMock);

        $this->operation->search($this->searchCriteriaMock);
    }

    /**
     * @expectedException \Picamator\CacheManager\Exception\InvalidCacheKeyException
     */
    function testInvalidKeyCacheSearch()
    {
        $cacheKey = 'internal_customer_1';
        $id = 1;

        // cache generator mock
        $this->keyGeneratorMock->expects($this->once())
            ->method('generateList')
            ->with($this->equalTo($this->searchCriteriaMock))
            ->willReturn([$id => $cacheKey]);

        // cache item pool mock
        $exception = new class extends \RuntimeException implements PsrCacheInvalidArgumentException {};

        $this->cacheItemPoolMock->expects($this->once())
            ->method('getItem')
            ->willThrowException($exception);

        // never
        $this->searchCriteriaMock->expects($this->never())
            ->method('getFieldList');
        $this->searchResultFactoryMock->expects($this->never())
            ->method('create');

        $this->operation->search($this->searchCriteriaMock);
    }
}
