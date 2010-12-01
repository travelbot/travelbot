<?php

/**
 * Description of HotelService
 *
 * @author Petr
 */
class HotelService
{

    private $lodgingTypes = array(
        "H" => 'Hotel',
        "C" => 'Condo',
        "A" => 'All-inclusive'
    );

    public function getHotels(IHotelMapper $mapper, $lat, $log,
            DateTime $startdate, DateTime $enddate, $rooms, $adults, $children)
    {
        $result = $mapper->searchHotels($lat, $log, $startdate, $enddate, $rooms, $adults, $children);
        if(strpos($result, "<Hotwire>") == false) {
            throw new BadRequestException('Hotel not found.');
        }
        $xmlResult = new SimpleXMLElement($results);
        if(!$xmlResult) {
            throw new BadRequestException('Hotel not found.');
        }
        return $this->parseResult($xmlResult->Result, $xmlResult->MetaData);
    }

    private function parseResult($xmlResult, $xmlMetaData)
    {
        $hotels = array();
        foreach ($xmlResult->HotelResult as $xmlHotelResult) {
            $hotels[] = $this->parseHotelResult($xmlHotelResult, $xmlMetaData);
        }
        return $hotels;
    }

    private function parseHotelResult($xmlHotelResult, $xmlMetaData)
    {
        $currency = $xmlHotelResult->CurrencyCode;
        $link = $xmlHotelResult->DeepLink;
        $totalPrice = $xmlHotelResult->TotalPrice;
        $amenity = $this->getAmenities($xmlHotelResult->AmenityCodes, $xmlMetaData);
        $lodgingType = $this->lodgingTypes[$xmlHotelResult->LodgingTypeCode];
        $starRating = $xmlHotelResult->StarRating;
        return new Hotel($currency, $link, $totalPrice, $amenity, $lodgingType, $starRating);
    }

    private function getAmenities($xmlAmenityCodes, $xmlMetaData)
    {
        $amenities = array();
        foreach ($xmlAmenityCodes->Code as $code) {
            $amenities[] = $this->findAmenity($code, $xmlMetaData);
        }
        return $amenities;
    }

    private function findAmenity($code, $xmlMetaData)
    {
        foreach ($xmlMetaData->HotelMetaData->Amenities->Amenity as $amenity) {
            if(strcmp($amenity->Code, $code) == 0) {
                return $amenity->Name;
            }
        }
    }

}

