<?php

use Nette\Application\BadRequestException;

/**
 * Service for Article objects
 *
 * @author Petr Valeš
 * @author Reviewed by mirteond  
 */
class ArticleService extends EntityService {

    /**
     *
     * @param string $destination
     * @param IArticleMapper $mapper
     * @return Article
     */
    public function buildArticle($destination, IArticleMapper $mapper)
	{
    	try {
        	return $this->findByDestination($destination);
        } catch (\Nette\Application\BadRequestException $e) {
        	try {
				$text = $mapper->getDestinationArticle($destination);
				$article = new Article($destination, $text);
				return $this->save($article);
			} catch (ArticleException $e) {
				return new Article($destination, 'Article about the destination was not found.');
			}
			
		}
    }

    /**
     *
     * @param Article $article
     * @return Article
     */
    public function save(Article $article)
    {
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
    public function find($id)
	{
        $article = $this->entityManager->find('Article', (int) $id);
        if ($article == NULL) {
            throw new BadRequestException('Article not found.');
        }
        return $article;
    }

	/**
     * @param int $id
     * @return Article
     * @throws Nette\Application\BadRequestException
     */     
    public function findByDestination($destination)
	{
        try {
			return $this->entityManager->createQuery('SELECT a FROM Article a WHERE a.destination = ?1')
	        	->setParameter(1, $destination)
	        	->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
			throw new BadRequestException('Article not found.', NULL, $e);
		}
    }

    /**
     *
     * @return array
     */
    public function findAll()
	{
        return $this->entityManager
                ->createQuery('SELECT a FROM Article a ORDER BY a.id ASC')
                ->getResult();
    }

}

