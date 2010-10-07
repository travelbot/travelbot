<?php

use Nette\Application\BadRequestException;

/**
 * Sample entity service.
 */
class SampleService extends EntityService
{

	public function create(array $data)
	{
		$sample = new Sample;
		$this->setData($sample, $data);
		
		$this->entityManager->persist($sample);
		$this->entityManager->flush();
		
		return $sample;
	}
	
	public function update(Sample $sample, array $data)
	{
		$this->setData($sample, $data);
		$this->entityManager->flush();
		
		return $sample;
	}
	
	public function find($id)
	{
		$sample = $this->entityManager->find('Sample', (int) $id);
		if ($sample == NULL) {
			throw new BadRequestException('Sample not found.');
		}
		return $sample;
	}
	
	public function findAll()
	{
		return $this->entityManager->getRepository('Sample')
			->findAll();
	}
	
	public function delete(Sample $sample)
	{
		$this->entityManager->remove($sample);
		$this->entityManager->flush();
	}
	
	private function setData(Sample $sample, array $data)
	{
		if (isset($data['title'])) $sample->title = $data['title'];
	}

}