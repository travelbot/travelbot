<?php

/**
 * Class for persisting article about destination
 *
 * @author Petr Valeš
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
     *
     * @var text
     * @column
     */
    private $article;

    public function __construct($destination, $article) {
        $this->destination = $destination;
        $this->article = $article;
    }

    /**
     *
     * @return string
     */
    public function getDestination() {
        return $this->destination;
    }

    /**
     *
     * @return text
     */
    public function getArticle() {
        return $this->article;
    }

}

