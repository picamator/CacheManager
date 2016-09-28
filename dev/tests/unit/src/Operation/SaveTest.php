<?php
namespace Picamator\CacheManager\Test\Unit\Operation;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManager\Operation\Save;

class SaveTest extends BaseTest
{
    /**
     * @var Save
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
     * @var \Picamator\CacheManager\Api\Cache\CacheItemFactoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheItemFactoryMock;

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

        $this->cacheItemFactoryMock = $this->getMockBuilder('Picamator\CacheManager\Api\Cache\CacheItemFactoryInterface')
            ->getMock();

        $this->operation = new Save(
            $this->keyGeneratorMock,
            $this->cacheItemPoolMock,
            $this->cacheItemFactoryMock
        );
    }

    public function testSave()
    {
        $id  = 1;
        $cacheKey = 'internal_customer_1';
        $idName = 'id';
        $data = [[$idName => $id, 'name' => 'Sergii']];

        // cache key mock
        $this->keyGeneratorMock->expects($this->once())
            ->method('generate')
            ->with($this->equalTo($id), $this->equalTo($this->searchCriteriaMock))
            ->willReturn($cacheKey);

        // search criteria mock
        $this->searchCriteriaMock->expects($this->once())
            ->method('getIdName')
            ->willReturn($idName);

        // cache item factory mock
        $cacheItemMock = $this->getMockBuilder('Psr\Cache\CacheItemInterface')
            ->getMock();

        $this->cacheItemFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo($cacheKey), $this->equalTo($data[0]))
            ->willReturn($cacheItemMock);

        // cache item pool mock
        $this->cacheItemPoolMock->expects($this->once())
            ->method('saveDeferred')
            ->with($this->equalTo($cacheItemMock));

        $this->cacheItemPoolMock->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $actual = $this->operation->save($this->searchCriteriaMock, $data);
        $this->assertTrue($actual);
    }

    /**
     * @dataProvider providerFailSave
     * @expectedException \Picamator\CacheManager\Exception\InvalidArgumentException
     *
     * @param array $data
     */
    public function testFailSave(array $data)
    {
        $idName = 'id';

        // search criteria mock
        $this->searchCriteriaMock->expects($this->once())
            ->method('getIdName')
            ->willReturn($idName);

        $this->operation->save($this->searchCriteriaMock, $data);
    }

    public function providerFailSave()
    {
        return [
            [
                [1, [], 2]
            ], [
                [['name' => 'Sergii'], ['id' => 1]]
            ]
        ];
    }
}
