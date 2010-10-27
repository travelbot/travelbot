<?php

/**
 * Description of WikipediaArticleMapper
 *
 * @author Petr Valeš
 */
class WikipediaArticleMapper implements IArticleMapper {

    public function getDestinationArticle($destination) {
        $wiki = new wiki('http://en.wikipedia.org/w/index.php');
        return $wiki->getpage($destination);
    }

}

class wiki {
    private $http;
    public $url;

    /**
     * This is our constructor.
     * @return void
     **/
    function __construct ($url='http://en.wikipedia.org/w/api.php') {
        $this->http = new http;
        $this->url = $url;
    }

    /**
     * Sends a query to the api.
     * @param $query The query string.
     * @return The api result.
     **/
    function query ($query) {
            $ret = $this->http->get($this->url.$query);
            echo $ret;
        return unserialize($ret);
    }

    /**
     * Gets the content of a page. Returns false on error.
     * @param $page The wikipedia page to fetch.
     * @param $revid The revision id to fetch (optional)
     * @return The wikitext for the page.
     **/
    function getpage ($page) {
        $x = $this->query('?title='.urlencode($page).'&action=render');
        return $x;
    }
}


class http {
    private $ch;
    private $uid;
    public $cookie_jar;
    public $getfollowredirs;

    function __construct () {
        $this->ch = curl_init();
        $this->uid = dechex(rand(0,99999999));
        curl_setopt($this->ch,CURLOPT_COOKIEJAR,'/tmp/cluewikibot.cookies.'.$this->uid.'.dat');
        curl_setopt($this->ch,CURLOPT_COOKIEFILE,'/tmp/cluewikibot.cookies.'.$this->uid.'.dat');
        curl_setopt($this->ch,CURLOPT_MAXCONNECTS,100);
        curl_setopt($this->ch,CURLOPT_CLOSEPOLICY,CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
        $this->getfollowredirs = 1;
        $this->cookie_jar = array();
    }

    function get ($url) {
        //echo 'GET: '.$url."\n";
        $time = microtime(1);
        curl_setopt($this->ch,CURLOPT_URL,$url);
        curl_setopt($this->ch,CURLOPT_USERAGENT,'php wikibot classes');
        /* Crappy hack to add extra cookies, should be cleaned up */
        $cookies = null;
        foreach ($this->cookie_jar as $name => $value) {
            if (empty($cookies))
                $cookies = "$name=$value";
            else
                $cookies .= "; $name=$value";
        }
        if ($cookies != null)
            curl_setopt($this->ch,CURLOPT_COOKIE,$cookies);
        curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION,$this->getfollowredirs);
        curl_setopt($this->ch,CURLOPT_MAXREDIRS,10);
        curl_setopt($this->ch,CURLOPT_HEADER,0);
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($this->ch,CURLOPT_TIMEOUT,30);
        curl_setopt($this->ch,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($this->ch,CURLOPT_HTTPGET,1);
        $data = curl_exec($this->ch);

        return $data;
    }

    function __destruct () {
        curl_close($this->ch);
        @unlink('/tmp/cluewikibot.cookies.'.$this->uid.'.dat');
    }
}

