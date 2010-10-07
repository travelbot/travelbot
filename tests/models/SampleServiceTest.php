<?php

class TestServiceTest extends TestCase
{

	/**
	 * @var TestService
	 */	 	
	private $service;

	protected function setUp()
	{
		$this->service = new SampleService($this->entityManager);
	}
	
	public function testCreate()
	{
		$a = $this->service->create(array(
			'title' => 'foo',
		));
		
		$this->assertEquals('foo', $a->title);
		
		return $a;
	}
	
	/**
	 * @depends testCreate
	 */	 	
	public function testFind(Sample $a)
	{
		$b = $this->service->find($a->id);
		$this->assertEquals($a->title, $b->title);
		
		return $b;
	}
	
	/**
	 * @depends testCreate
	 */	 	
	public function testUpdate(Sample $a)
	{
		$this->service->update($a, array(
			'title' => 'bar',
		));
		
		$this->assertEquals('bar', $a->title);
	}
	
	/**
	 * @depends testFind
	 * @expectedException Nette\Application\BadRequestException	 
	 */
	public function testDeleteAndNotFound(Sample $a)
	{
		$id = $a->id;
		$this->service->delete($a);
		$this->service->find($id);
	}
	
	public function testFindAll()
	{
		$result = $this->service->findAll();
		$this->assertType('array', $result);
	}

}