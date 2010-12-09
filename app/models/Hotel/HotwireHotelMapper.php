<?php

use Nette\Web\Uri;
/**
 * Description of HotwireHotelMapper
 *
 * @author Petr
 */
class HotwireHotelMapper implements IHotelMapper
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function searchHotels($lat, $log, DateTime $startdate, DateTime $enddate, $rooms,
            $adults, $children)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
        //curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $uri = new Uri('http://api.hotwire.com/v1/search/hotel');
        $uri->setQuery(array(
            'apikey' => $this->key,
            'dest' => $lat . ',' . $log,
            'startdate' => $startdate->format("m/d/Y"),
            'enddate' => $enddate->format("m/d/Y"),
            'rooms' => $rooms,
            'adults' => $adults,
            'children' => $children
        ));

        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);

        return $result;
    }

}

