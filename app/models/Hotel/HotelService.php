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
        if (strpos($result, "<Hotwire>") == false) {
            throw new BadRequestException('Hotel not found.');
        }
        $xmlHotwire = new SimpleXMLElement($result);
        if (!$xmlHotwire) {
            throw new BadRequestException('Hotel not found.');
        }
        $xmlResult = $xmlHotwire->Result;
        $xmlMetaData = $xmlHotwire->MetaData;
        return $this->parseResult($xmlResult, $xmlMetaData);
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
        $xmlAmenityCodes = $xmlHotelResult->AmenityCodes;
        $amenity = $this->getAmenities($xmlAmenityCodes, $xmlMetaData);
        $xmlLodgingTypeCode = $xmlHotelResult->LodgingTypeCode;
        $lodgingTypeCode = (string)$xmlLodgingTypeCode;
        $lodgingType = $this->lodgingTypes[$lodgingTypeCode];
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
        $xmlHotelMetaData = $xmlMetaData->HotelMetaData;
        $xmlAmenities = $xmlHotelMetaData->Amenities;
        foreach ($xmlAmenities->Amenity as $amenity) {
            if (strcmp($amenity->Code, $code) == 0) {
                return $amenity->Name;
            }
        }
    }

}

