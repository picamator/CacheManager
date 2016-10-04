<?php
namespace Picamator\CacheManager\Tests\Unit;

use Picamator\CacheManager\CacheManager;

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
	 * @var \Picamator\CacheManager\Api\Operation\DeleteInterface | \PHPUnit_Framework_MockObject_MockObject
	 */
	private $operationDeleteMock;
	
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
		$this->operationDeleteMock = $this->getMockBuilder('Picamator\CacheManager\Api\Operation\DeleteInterface')
			->getMock();
		
		$this->searchCriteriaMock = $this->getMockBuilder('Picamator\CacheManager\Api\Data\SearchCriteriaInterface')
			->getMock();	
		
		$this->cacheManager = new CacheManager($this->operationSaveMock, $this->operationSearchMock, $this->operationDeleteMock);
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

    public function testDelete()
    {
        // operation delete mock
        $this->operationDeleteMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($this->searchCriteriaMock));

        $this->cacheManager->delete($this->searchCriteriaMock);
    }
}
