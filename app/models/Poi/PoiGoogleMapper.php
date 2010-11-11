<?php

use Nette\Web\Uri;

/**
 * Description of POICurlMapper
 *
 * @author Petr Vales
 */
class PoiGoogleMapper extends Nette\Object implements IPoiMapper {

    private $clientID;
    private $key;

    public function __construct($id, $key){
        $this->setClientID($id);
        $this->setKey($key);
    }
    
    public function getClientId()
    {
		return $this->clientID;
	}

    public function setClientId($id) {
        $this->clientID = $id;
    }
    
    public function getKey()
    {
		return $this->key;
	}

    private function setKey($key) {
        $this->key = $key;
    }

    public function getPointsOfInterest($lat, $lng) {
        $json_details = array();
        $json = $this->searchPlaces($lat.", ".$lng);
        foreach ($json->results as $result) {
            $json_details[] = $this->searchDetails($result->reference);
        }
        return $json_details;
    }

    private function searchPlaces($location) {
        // curl initialization
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $uri = new Uri('https://maps.googleapis.com/maps/api/place/search/json');
        $uri->setQuery(array(
            'location' => $location,
			'radius ' => '250',
        ));
        
        $uri->appendQuery(array(
			'signature' => $this->getSignature($uri),
		));


        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);

        $json = json_decode($result);
        if ($json == FALSE || $json->status != 'OK') {
            throw new InvalidStateException('Malformed JSON response.');
        }
        return $json;
    }

    private function searchDetails($reference)  {
        // curl initialization
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $uri = new Uri('https://maps.googleapis.com/maps/api/place/details/json');
        $uri->setQuery(array(
            'reference' => $reference,
        ));
        
        $uri->appendQuery(array(
			'signature' => $this->getSignature($uri),
		));

        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);

        $json = json_decode($result);
        if ($json == FALSE || $json->status != 'OK') {
            throw new InvalidStateException('Malformed JSON response.');
        }
        return $json;
    }

    private function getSignature(Uri $absoluteUri)
    {
		$uri = new Uri($absoluteUri->path . $absoluteUri->query);
        $uri->setQuery(array(
            'sensor' => 'false',
            'client' => $this->clientID,
        ));

        return hash_hmac('sha1', $uri->path . '?' . $uri->query, $this->key);
	}

}
