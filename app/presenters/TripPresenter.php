<?php

/**
 * Presenter for listing and showing details of saved trips.
 * @author mirteond 
 */
class TripPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$service = new TripService($this->entityManager);
		$this->template->trips = $service->findAll();
	}
	
	public function renderShow($id)
	{
		$service = new TripService($this->entityManager);
		$trip = $service->find($id);
		$this->template->trip = $trip;
		
        $articleService = new ArticleService($this->entityManager);
        $this->template->article = $articleService->buildArticle(
			$trip->arrival,
			new ArticleWikipediaMapper()
		);
	}

}
