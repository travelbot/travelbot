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
        $event1->setDate(new DateTime('07.12.1988 04:20'));

        $trip1 = new Trip('Praha', 'Brno');

        $event1->addTrip($trip1);

        $trip2 = new Trip('Praha', 'Dresden');

        $event1->addTrip($trip2);

        $service->save($event1);
        
        $eventFound = $service->find($event1->id);
        $this->assertEquals($event1, $eventFound);
        
        }
}