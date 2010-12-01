<?php
/**
 * Description of IHotelMapper
 *
 * @author Petr
 */
interface IHotelMapper {
    public function searchHotels($lat, $log, DateTime $startdate, DateTime $enddate, $rooms, $adults, $children);
}

