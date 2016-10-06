<?php
namespace Picamator\CacheManager\Tests\Unit\Cache;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManager\Cache\KeyGenerator;

class CacheKeyTest extends BaseTest
{
    /**
     * @var KeyGenerator
     */
    private $keyGenerator;

    /**
     * @var \Picamator\CacheManager\Api\Data\SearchCriteriaInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaMock;

    protected function setUp()
    {
        parent::setUp();

        $this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
            ->getMock();

        $this->keyGenerator = new KeyGenerator();
    }

    /**
     * @dataProvider    providerGenerator
     *
     * @param int       $id
     * @param string    $entityName
     * @param string    $contextName
     * @param string    $expected
     */
    public function testGenerator(
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
        $this->keyGenerator->generate($id, $this->searchCriteriaMock);
        $actual = $this->keyGenerator->generate($id, $this->searchCriteriaMock);
        $this->assertEquals($expected, $actual);
    }

    public function testDuplicateIdGenerator()
    {
        $id = 1;
        $entityName = 'customer';

        // search criteria mock in _first_context_
        $searchCriteriaFirstMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
            ->getMock();

        $searchCriteriaFirstMock->expects($this->once())
            ->method('getContextName')
            ->willReturn('first_context');

        $searchCriteriaFirstMock->expects($this->once())
            ->method('getEntityName')
            ->willReturn($entityName);

        // search criteria mock in _second_context_
        $searchCriteriaSecondMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
            ->getMock();

        $searchCriteriaSecondMock->expects($this->once())
            ->method('getContextName')
            ->willReturn('second_context');

        $searchCriteriaSecondMock->expects($this->once())
            ->method('getEntityName')
            ->willReturn($entityName);

        $keyFirst = $this->keyGenerator->generate($id, $searchCriteriaFirstMock);
        $keySecond = $this->keyGenerator->generate($id, $searchCriteriaSecondMock);

        $this->assertNotEquals($keyFirst, $keySecond);
    }

    public function testGenerateList()
    {
        $idList = [1, 2, 3];

        // search criteria mock
        $this->searchCriteriaMock->expects($this->once())
            ->method('getIdList')
            ->willReturn($idList);

        // cache key generator mock
        /** @var \Picamator\CacheManager\Cache\KeyGenerator | \PHPUnit_Framework_MockObject_MockObject $cacheKeyGeneratorMock */
        $cacheKeyGeneratorMock = $this->getMockBuilder('Picamator\CacheManager\Cache\KeyGenerator')
            ->setMethods(['generate'])
            ->getMock();

        $cacheKeyGeneratorMock->expects($this->exactly(count($idList)))
            ->method('generate');

        $actual = $cacheKeyGeneratorMock->generateList($this->searchCriteriaMock);
        $this->assertNotEmpty($actual);
        $this->assertCount(count($idList), $actual);
    }

    public function providerGenerator()
    {
        return [
            [1, 'customer', '', 'customer_1'],
            [2, 'customer', 'cloud', 'cloud_customer_2']
        ];
    }
}
