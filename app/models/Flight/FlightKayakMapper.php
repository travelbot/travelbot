<?php

use Nette\Web\Uri;

define("TOKEN", 'yf_Xn6gnJEcpr$3mHKV2Ig'); //This is your developer key.
define("VERSION", '1');                    //The version of the API the client is expecting. The only current supported version is "1"
define("APIMODE", '1');                    //must be "1"
define("ACTION", 'doFlights');             //must be "doFlights"
define("ANY_TIME", 'a');                   //Values:"a" = any time; "r"=early morning; "m"=morning; "12"=noon; "n"=afternoon; "e"=evening; "l"=night
define("BASIC_MODE", 'true');              //must be "true"
define("C", '3');                         //integer, the number of results to return
define("M", 'normal');                     //filter mode: normal or airline:?? where ?? is a two-letter airline code
define("D", 'up');                       //sort direction: up, down
define("S", 'price');                   //sort key: price, duration, depart, arrive, airline

/**
 * 
 *
 * @author Petr
 */
class FlightKayakMapper implements IFlightMapper
{

    private $sessionId;

    public function __construct()
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        //curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $uri = new Uri('http://www.kayak.com/k/ident/apisession');
        $uri->setQuery(array(
            'token' => TOKEN,
            'version' => VERSION
        ));

        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);

        $this->setSessrionId($result);
    }

    public function searchFlights($from, $to, DateTime $depart_date,
            DateTime $return_date, $travelers, $cabin, $oneWay)
    {
        $xml = $this->startFlightSearch($from, $to, $depart_date, $return_date, $travelers, $cabin);
        $searchId = $this->getSearchId($xml);
        sleep(20);
        return $this->getFlightResults($searchId);
    }

    protected function startFlightSearch($from, $to, DateTime $depart_date,
            DateTime $return_date, $travelers, $cabin, $oneWay = 'y')
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        //curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $uri = new Uri('http://www.kayak.com/s/apisearch');
        $uri->setQuery(array(
            'basicmode' => BASIC_MODE,
            'oneway' => $oneWay,
            'origin' => $from,
            'destination' => $to,
            'depart_date' => $depart_date->format("m/d/Y"),
        ));
        if ($return_date != null)
        {
            $uri->appendQuery(array('$return_date' => $return_date->format("m/d/Y")));
        }
        $uri->appendQuery(array('depart_time' => ANY_TIME));
        if ($return_date != null)
                $uri->appendQuery(array('return_time' => ANY_TIME));
        $uri->appendQuery(array(
            'travelers' => $travelers,
            'cabin' => $cabin,
            'action' => ACTION,
            'apimode' => APIMODE,
            "_sid_" => $this->getSessionId(),
            'version' => VERSION
        ));

        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);
        return $result;
    }

    protected function getFlightResults($searchid)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        //curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $uri = new Uri('http://www.kayak.com/s/apibasic/flight');
        $uri->setQuery(array(
            'searchid' => $searchid,
            'c' => C,
            'm' => M,
            'd' => D,
            's' => S,
            'apimode' => APIMODE,
            "_sid_" => $this->getSessionId(),
            'version' => VERSION
        ));


        curl_setopt($c, CURLOPT_URL, (string) $uri);
        $result = curl_exec($c);
        curl_close($c);
        return $result;
    }

    protected function getSessionId()
    {
        return $this->sessionId;
    }

    protected function setSessrionId($xml)
    {
        $xmlSessionId = new SimpleXMLElement($xml);
        if (!$xmlSessionId)
        {
            throw new FlightException("Session ID error", null, null);
        }
        $this->sessionId = (string) $xmlSessionId->sid;
    }

    protected function getSearchId($xml)
    {
        $xmlSearchId = new SimpleXMLElement($xml);
        if (!$xmlSearchId)
        {
            throw new FlightException("Search ID error", null, null);
        }
        return (string) $xmlSearchId->searchid;
    }

}

