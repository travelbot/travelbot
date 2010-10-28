<?php

/**
 * Class for persisting article about destination
 *
 * @author Petr Valeš
 * @author Reviewed by mirteond 
 *
 * @entity
 * @table(name="article")
 */
class Article extends SimpleEntity {

    /**
     *
     * @var string
     * @column
     */
    private $destination;
    
    /**
     * @var string
     * @column
     */
    private $text;

	/**
	 * @param string
	 * @param string	 
	 */
    public function __construct($destination, $text) {
        $this->destination = $destination;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getDestination() {
        return $this->destination;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }
    
    public function setText($text)
    {
		$this->text = $text;
		return $this;
	}

}

