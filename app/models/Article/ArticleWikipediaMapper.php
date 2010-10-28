<?php

use Nette\Web\Uri;
use Nette\String;

/**
 * Description of WikipediaArticleMapper
 *
 * @author Petr Valeš
 * @author Reviewed by mirteond  
 */
class ArticleWikipediaMapper extends Nette\Object implements IArticleMapper {

    public function getDestinationArticle($destination) {
        $c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($c, CURLOPT_USERAGENT, 'Travelbot 1.0 beta');
		
		// using Nette\Web\Uri for escaping GET parameters
		$uri = new Uri('http://en.wikipedia.org/w/index.php');
		$uri->setQuery(array(
			'title' => $destination,
			//'action' => 'render',
		));
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);

		// getting first true paragraph
		$correct = FALSE;
		$pos = -3;
		$i = 0;
		while(!$correct && $i < 5) {
			$pos = mb_strpos($result, '<p>', $pos + 3, 'UTF-8');
			$cropped = mb_substr($result, $pos);
			$paragraph = mb_substr($cropped, 0, mb_strpos($cropped, '</p>') + 4);
			if (String::startsWith(strip_tags($paragraph), $destination)) {
				return $paragraph;	
			}
			$i++;
			
		}
		
		throw new ArticleException('Article not found.');
    }

}
