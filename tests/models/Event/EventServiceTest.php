<?php

class EventServiceTest extends TestCase
{

     // includes testfindall
    public function testSave(){

        $service = new EventService($this->entityManager);
        $event1 = new Event();
        $event1->setTitle('Titulo1');
        $event1->setUrl('Url1');
        $event1->setDescription('descripcion1');
        $event1->setVenue(new Venue('Desc', 'Url2'));

        $trip1 = new Trip('Praha', 'Brno');

        $event1->addTrip($trip1);

        $trip2 = new Trip('Praha', 'Dresden');

        $event1->addTrip($trip2);
        
        $count=count($service->findAll());
        $service->save($event1);
        $this->assertEquals($count+1, count($service->findAll()));
        }

     // Proof example Event with id=2 witch already exists on database.
        public function testFind(){
        $service = new EventService($this->entityManager);
        $event2=$service->find(2);
        $this->assertEquals($event2->getId(),2);
        }
}