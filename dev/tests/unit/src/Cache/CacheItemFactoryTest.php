<?php
namespace Picamator\CacheManager\Tests\Unit\Cache;

use Picamator\CacheManager\Cache\CacheItemFactory;
use Picamator\CacheManager\Tests\Unit\BaseTest;

class CacheItemFactoryTest extends BaseTest
{
    /**
     * @var CacheItemFactory
     */
    private $cacheItemFactory;

    /**
     * @var \Picamator\CacheManager\Api\ObjectManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManagerMock = $this->getMockBuilder('Picamator\CacheManager\Api\ObjectManagerInterface')
            ->getMock();

        $this->cacheItemFactory = new CacheItemFactory($this->objectManagerMock);
    }

    public function testCreate()
    {
        $key = 'key';
        $value = ['name' => 'Sergii'];
        $className = '\Cache\Adapter\Common\CacheItem';

        // object manager mock
        $cacheItemMock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        $cacheItemMock->expects($this->once())
            ->method('set')
            ->with($this->equalTo($value))
            ->willReturnSelf();

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo($className), $this->equalTo([$key]))
            ->willReturn($cacheItemMock);

        $this->cacheItemFactory->create($key, $value);
    }
}
