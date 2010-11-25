<?php

use Nette\Web\Uri;

/**
 * @author mirteond
 */
class PoiGowallaMapper extends Nette\Object implements IPoiMapper
{

	private $apiKey;
	private $radius = 50;
	
	/** @var CurlAsync */
	private $curl;
	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
		$this->curl = new CurlAsync;
	}

	public function getPois($latitude, $longitude)
	{
		$coordinates = array(
			array($latitude, $longitude),
			array($latitude + 0.015, $longitude + 0.02),
			array($latitude - 0.015, $longitude - 0.02),
			array($latitude + 0.015, $longitude - 0.02),
			array($latitude - 0.015, $longitude + 0.02),
			array($latitude, $longitude + 0.02),
			array($latitude, $longitude - 0.02),
			array($latitude + 0.015, $longitude),
			array($latitude - 0.015, $longitude),
		);
		
		foreach($coordinates as $coord) {
			$this->beginCurl($coord[0], $coord[1]);
		}
		
		$pois = array();
		foreach($coordinates as $coord) {
			$pois = array_merge($pois, $this->processCurl($coord[0], $coord[1]));
		}
		
		return $pois;
        
	}
	
	private function beginCurl($latitude, $longitude)
	{
		$uri = new Uri('http://api.gowalla.com/spots');
        $uri->setQuery(array(
            'lat' => (string) $latitude,
			'lng' => (string) $longitude,
			'radius' => (string) $this->radius,
        ));
        
        return $this->curl->{$latitude . $longitude}((string) $uri, array(
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
				'Content-Type: application/json',
				'X-Gowalla-API-Key: ' . $this->apiKey,
			),
			CURLOPT_FOLLOWLOCATION => TRUE,
		));
	}
	
	private function processCurl($latitude, $longitude)
	{
		$json = json_decode($this->curl->{$latitude . $longitude}());
        if ($json == FALSE) {
            throw new InvalidStateException('Malformed JSON response.');
        }
        
        $spots = $json->spots;
        
        $pois = array();
        foreach($spots as $spot) {
			$poi = new Poi;
			$poi->name = $spot->name;
			$poi->address = $spot->address->locality;
			$poi->latitude = $spot->lat;
			$poi->longitude = $spot->lng;
			$poi->url = 'http://gowalla.com' . $spot->url;
			
			$id = explode('/', $spot->spot_categories[0]->url);
			$poi->imageUrl = GowallaCategoryImage::get($id[count($id)-1]);
			
			$types = array();
			foreach($spot->spot_categories as $category) {
				$types[] = $category->name;
			}
			$poi->types = implode(', ', $types);
			
			$pois[] = $poi;
		}
		
		return $pois;
	}

}
