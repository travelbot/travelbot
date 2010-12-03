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

        $poi2 = new Poi();
        $poi2->setName('Poi2');
        $poi2->setImageUrl('Url2');
        $poi2->setAddress('Address2');
        $poi2->setTypes('Types2');
        $poi2->setUrl('Url2');

        $count=count($service->findAll());
        $service->save($poi1);
        $service->save($poi2);
        $this->assertEquals($count+2, count($service->findAll()));
        }
        // Poi with id=3 already exists on database.
        public function testFind(){
        $service = new PoiService($this->entityManager);
        $poi2=$service->find(3);
        $this->assertEquals($poi2->getId(),3);
        }
       
}
