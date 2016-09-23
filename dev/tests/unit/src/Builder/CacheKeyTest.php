<?php
namespace Picamator\CacheManager\Tests\Unit\Builder;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManager\Builder\CacheKey;

class CacheKeyTest extends BaseTest
{
    /** @var CacheKey */
    private $cacheKey;

    /** @var \Picamator\CacheManager\Api\Data\SearchCriteriaInterface | \PHPUnit_Framework_MockObject_MockObject */
    private $searchCriteriaMock;

    protected function setUp()
    {
        parent::setUp();

        $this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
            ->getMock();

        $this->cacheKey = new CacheKey();
    }

    /**
     * @dataProvider providerBuild
     *
     * @param int       $id
     * @param string    $entityName
     * @param string    $contextName
     * @param string    $expected
     */
    public function testBuild(
        int $id,
        string $entityName,
        string $contextName,
        string $expected
    ) {
        // search criteria mock
        $this->searchCriteriaMock->expects($this->once())
            ->method('getEntityName')
            ->willReturn($entityName);

        $this->searchCriteriaMock->expects($this->once())
            ->method('getContextName')
            ->willReturn($contextName);

        // test result as well as duplication run
        $this->cacheKey->build($id, $this->searchCriteriaMock);
        $actual = $this->cacheKey->build($id, $this->searchCriteriaMock);
        $this->assertEquals($expected, $actual);
    }

    public function providerBuild()
    {
        return [
            [1, 'customer', '', 'customer_1'],
            [2, 'customer', 'cloud', 'cloud_customer_2']
        ];
    }
}
