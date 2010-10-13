<?php

class LocationService extends Service
{

	/**
	 * Returns Location found by GPS coordinates.
	 * @param string
	 * @param string
	 * @return Location	 	 	 
	 */
	public function getLocation($latitude, $longitude)
	{
		// curl initialization
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($c, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false');
		$result = curl_exec($c);
		curl_close($c);

		$city = '';
		$country = '';
		$street = '';
		
		// parsing json results
		$comps = get_object_vars(json_decode($result));
		foreach($comps['results'][0]->address_components as $comp) {
			if ($comp->types == array('locality', 'political')) {
				$city = $comp->short_name;
			}
			if ($comp->types == array('country', 'political')) {
				$country = $comp->long_name;
			}
			if ($comp->types == array('route')) {
				$street = $comp->long_name;
			}
		}
		
		return new Location($street, $city, $country);
	}

}
