<?php

/**
 * Description of POICurlMapper
 *
 * @author Petr Vales
 */
class POICurlMapper implements IPOIMapper {

    public function getPointsOfInterest($location) {
        $json_details = array();
        $json = searchPlaces($location);
        foreach ($json->results as $result) {
            $json_details[] = searchDetails($result->reference);
        }
        return $json_details;
    }

    function searchPlaces($location) {
        // curl initialization
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        // using Nette\Web\Uri for escaping GET parameters
        $uri = new Uri('/maps/api/place/search/json');
        $uri->setQuery(array(
            'location' => $location,
            'radius ' => '250',
            'sensor' => 'false',
            'client' => 'travelbot',            // VYPLINT CLIENT ID
        ));

        $signature = hash_hmac ( 'sha1' , (string) $uri , 'key' ); // VYPLNIT KLIC

        $uri = new Uri('https://maps.googleapis.com/maps/api/place/search/json');
        $uri->setQuery(array(
            'location' => $location,
            'radius ' => '250',
            'sensor' => 'false',
            'client' => 'travelbot',            // VYPLINT CLIENT ID
            'signature' => $signature
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

    function searchDetails($reference)  {
        // curl initialization
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        // using Nette\Web\Uri for escaping GET parameters
        $uri = new Uri('/maps/api/place/details/json');
        $uri->setQuery(array(
            'reference' => $reference,
            'sensor' => 'false',
            'client' => 'travelbot',            // VYPLINT CLIENT ID
        ));

        $signature = hash_hmac ( 'sha1' , (string) $uri , 'key' ); // VYPLNIT KLIC

        $uri = new Uri('https://maps.googleapis.com/maps/api/place/details/json');
        $uri->setQuery(array(
            'reference' => $reference,
            'sensor' => 'false',
            'client' => 'travelbot',            // VYPLINT CLIENT ID
            'signature' => $signature
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


}

