<?php
namespace Picamator\CacheManager\Tests\Unit;

use Picamator\CacheManager\ObjectManager;

class ObjectManagerTest extends BaseTest
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager();
    }

    public function testCreate()
    {
        $className = '\DateTime';
        $arguments = ['now'];

        $actual = $this->objectManager->create($className, $arguments);
        $this->assertInstanceOf($className, $actual);
    }

    /**
     * @expectedException \Picamator\CacheManager\Exception\RuntimeException
     */
    public function testFailCreate()
    {
        $this->objectManager->create('\Picamator\CacheManager\Cache\KeyGenerator', [1, 2]);
    }
}
