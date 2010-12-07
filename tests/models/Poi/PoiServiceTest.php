<?php

class PoiServiceTest extends TestCase
{

       //testsave and testfindall
        public function testSave(){
	      $service = new PoiService($this->entityManager);
	      $poi1 = new Poi();
	      $poi1->setName('Poi1');
	      $poi1->setImageUrl('Url1');
	      $poi1->setAddress('Address1');
	      $poi1->setTypes('Types1');
	      $poi1->setUrl('Url1');
	      
	      $service->save($poi1);
	
	      $poiFound = $service->find($poi1->id);
	      $this->assertEquals($poi1, $poiFound);
	      
        }
       
}
