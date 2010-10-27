<?php

use Nette\Application\BadRequestException;

/**
 * Service for Article objects
 *
 * @author Petr Valeš
 */
class ArcticleService extends EntityService {

    /**
     *
     * @param string $destination
     * @param IArticleMapper $mapper
     * @return Article
     */
    public function buildeArticle($destination, IArticleMapper $mapper) {
        $article = $this->findByDestination($destination);
        if ($article == NULL)   {
                $text = $mapper->getDestinationArticle($destination);
                $article = new Article($destination, $text);
        }
        return $article;
    }

    /**
     *
     * @param Article $article
     * @return Article
     */
    public function save(Article $article) {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $article;
    }

    /**
     *
     * @param int $id
     * @return Article
     * @throws Nette\Application\BadRequestException
     */
    public function findById($id) {
        $article = $this->entityManager->find('Article', (int) $id);
        if ($article == NULL) {
            throw new BadRequestException('Article not found.');
        }
        return $article;
    }

    public function findByDestination($destination) {
        $query = $this->entityManager->createQuery("SELECT a FROM Article a WHERE a.destination = \'".$destination."\'");
        return $query->getResult();
    }

    /**
     *
     * @return array
     */
    public function findAll() {
        return $this->entityManager
                ->createQuery('SELECT a FROM Article a ORDER BY a.id ASC')
                ->getResult();
    }

}

?>
