<?php

use Nette\Web\Uri;

/**
 * Description of WikipediaArticleMapper
 *
 * @author Petr Valeš
 */
class ArticleWikipediaMapper extends Nette\Object implements IArticleMapper {

    public function getDestinationArticle($destination) {
        $c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($c, CURLOPT_USERAGENT, 'Travelbot 1.0 beta');
		
		// using Nette\Web\Uri for escaping GET parameters
		$uri = new Uri('http://en.wikipedia.org/w/api.php');
		$uri->setQuery(array(
			'format' => 'json',
			'titles' => $destination,
			'action' => 'query',
			'prop' => 'revisions',
			'rvprop' => 'content',
		));
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);
		
		$json = json_decode($result);
		if ($json == FALSE) {
			throw new InvalidStateException('Malformed JSON response.');
		}
		
		foreach($json->query->pages as $page) {
			$text = $page->revisions[0]->{'*'};
			break;
		}
		
		if (!isset($text)) {
			throw new InvalidStateException('Text not found.');
		}
		
		var_dump($this->parseText($text)); die;
    }
    
    private function parseText($text)
    {
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($c, CURLOPT_USERAGENT, 'Travelbot 1.0 beta');
		
		// using Nette\Web\Uri for escaping GET parameters
		$uri = new Uri('http://en.wikipedia.org/w/api.php');
		$uri->setQuery(array(
			'format' => 'json',
			'text' => $text,
			'action' => 'parse',
		));
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);
		var_dump($result); die;
		$json = json_decode($result);
		if ($json == FALSE) {
			throw new InvalidStateException('Malformed JSON response.');
		}
		return $json;
	}

}
