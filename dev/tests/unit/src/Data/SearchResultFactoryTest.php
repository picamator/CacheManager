<?php
namespace Picamator\CacheManager\Tests\Unit\Data;

use Picamator\CacheManager\Data\SearchResultFactory;
use Picamator\CacheManager\Tests\Unit\BaseTest;

class SearchResultFactoryTest extends BaseTest
{
    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var \Picamator\CacheManager\Api\ObjectManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManagerMock = $this->getMockBuilder('Picamator\CacheManager\Api\ObjectManagerInterface')
            ->getMock();

        $this->searchResultFactory = new SearchResultFactory($this->objectManagerMock);
    }

    public function testCreate()
    {
        $data = [];
        $missedData = [];
        $className = '\Picamator\CacheManager\Data\SearchResult';

        // object manager mock
        $cacheItemMock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo($className), $this->equalTo([$data, $missedData]))
            ->willReturn($cacheItemMock);

        $this->searchResultFactory->create($data, $missedData);
    }
}
