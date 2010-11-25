<?php

use Nette\Web\Uri;

/**
 * @author mirteond
 */
class PoiGowallaMapper extends Nette\Object implements IPoiMapper
{

	private $apiKey;
	private $radius = 50;
	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function getPois($latitude, $longitude)
	{
		$pois = $this->getSpecificPois($latitude, $longitude);
		/*$pois = array_merge($pois, $this->getSpecificPois($latitude + 0.015, $longitude + 0.02));
		$pois = array_merge($pois, $this->getSpecificPois($latitude - 0.015, $longitude - 0.02));
		$pois = array_merge($pois, $this->getSpecificPois($latitude + 0.015, $longitude - 0.02));
		$pois = array_merge($pois, $this->getSpecificPois($latitude - 0.015, $longitude + 0.02));
		
		$pois = array_merge($pois, $this->getSpecificPois($latitude, $longitude + 0.02));
		$pois = array_merge($pois, $this->getSpecificPois($latitude, $longitude - 0.02));
		$pois = array_merge($pois, $this->getSpecificPois($latitude + 0.015, $longitude));
		$pois = array_merge($pois, $this->getSpecificPois($latitude - 0.015, $longitude));*/
		
		return $pois;
        
	}
	
	private function getSpecificPois($latitude, $longitude)
	{
		$c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',
			'X-Gowalla-API-Key: ' . $this->apiKey,
		));
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
        
        $uri = new Uri('http://api.gowalla.com/spots');
        $uri->setQuery(array(
            'lat' => (string) $latitude,
			'lng' => (string) $longitude,
			'radius' => (string) $this->radius,
        ));
        
        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);

        $json = json_decode($result);
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
