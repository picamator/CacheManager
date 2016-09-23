<?php
namespace Picamator\CacheManager\Tests\Unit;

use Picamator\CacheManager\Tests\Unit\BaseTest;
use Picamator\CacheManage\CacheManager;

class CacheManagerTest extends BaseTest
{	
	/**
	 * @var CacheManager
	 */
	private $cacheManager;
	
	/**
	 * @var \Picamator\CacheManager\Api\Operation\SaveInterface | \PHPUnit_Framework_MockObject_MockObject
	 */
	private $operationSaveMock;
	
	/**
	 * @var \Picamator\CacheManager\Api\Operation\SearchInterface | \PHPUnit_Framework_MockObject_MockObject
	 */
	private $operationSearchMock;
	
	/**
	 * @var \Picamator\CacheManager\Api\Operation\InvalidateInterface | \PHPUnit_Framework_MockObject_MockObject
	 */
	private $operationInvalidateMock;
	
	/**
	 * @var \Picamator\CacheManager\Api\Operation\SearchCriteriaInterface | \PHPUnit_Framework_MockObject_MockObject
	 */
	private $searchCriteriaMock;
	
	protected function setUp() 
	{
		parent::setUp();
		
		$this->operationSaveMock = $this->getMockBuilder('Picamator\CacheManager\Api\Operation\SaveInterface')
			->getMock();
		$this->operationSearchMock = $this->getMockBuilder('Picamator\CacheManager\Api\Operation\SearchInterface')
			->getMock();
		$this->operationInvalidateMock = $this->getMockBuilder('Picamator\CacheManager\Api\Operation\InvalidateInterface')
			->getMock();
		
		$this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Api\Operation\SearchCriteriaInterface')
			->getMock();	
		
		$this->cacheManager = new CacheManager($this->operationSave, $this->operationSearch, $this->operationInvalidate);
	}
	
	public function testSave() 
	{
	    $data = [];

		// operation save mock
		$this->operationSaveMock->expects($this->once())
			->method('save')
			->with($this->equalTo($this->searchCriteriaMock), $this->equalTo($data));

        $this->cacheManager->save($this->searchCriteriaMock, $data);
	}

    public function testSearch()
    {
        // operation search mock
        $this->operationSearchMock->expects($this->once())
            ->method('search')
            ->with($this->equalTo($this->searchCriteriaMock));

        $this->cacheManager->search($this->searchCriteriaMock);
    }

    public function testInvalidate()
    {
        // operation invalidate mock
        $this->operationInvalidateMock->expects($this->once())
            ->method('invalidate')
            ->with($this->equalTo($this->searchCriteriaMock));

        $this->cacheManager->invalidate($this->searchCriteriaMock);
    }
}
