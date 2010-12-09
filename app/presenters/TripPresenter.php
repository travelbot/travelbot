<?php

use Nette\Environment;
use Nette\Application\AppForm;
use Nette\Forms\FormControl;

/**
 * Presenter for listing and showing details of saved trips.
 * @author mirteond 
 */
class TripPresenter extends BasePresenter
{

	/** @persistent */
	public $id;
	
	/** @var Trip */
	private $trip;
	
	public function startup()
	{
		parent::startup();
		$service = new TripService($this->entityManager);
		$this->trip = $service->find($this->id);
	}
	
	protected function createComponentDateForm()
	{
		$form = new AppForm;

        $dateValidate = function(FormControl $control) {
			try {
				$dt = new DateTime($control->value);
				return TRUE;
			} catch (Exception $e) {
				return FALSE;
			}
		};
                
        $form->addText('fromDate', 'Departure')
        	->setRequired('Departure date must be filled.')
        	->addRule($dateValidate, 'Date "from" must be in valid format.');
                
        $form->addText('toDate', 'Arrival')
        	->setRequired('Arrival date must be filled.')
        	->addRule($dateValidate, 'Date "to" must be in valid format.');
        		
        $form->addSubmit('okSubmit', 'Save');
        $form->onSubmit[] = array($this, 'submitDateForm');
        
        $form->elementPrototype->class('ajax');
        
        return $form;
	}
	
	public function submitDateForm(AppForm $form)
	{
		$values = $form->values;
		$this->trip->departureDate = new DateTime($values['fromDate']);
		$this->trip->arrivalDate = new DateTime($values['toDate']);
		
		$service = new TripService($this->entityManager);
		$service->save($this->trip);
		
		$this->terminate();
	}
	
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->trip = $this->trip;
	}
	
	public function renderShow()
	{
		$dt = new DateTime;
		$to = clone $dt;
		$to->modify('+1 week');
		$this['dateForm']->setDefaults(array(
			'fromDate' => $this->trip->departureDate != NULL ? $this->trip->departureDate->format('d.m.Y') : $dt->format('d.m.Y'),
			'toDate' => $this->trip->arrivalDate != NULL ? $this->trip->arrivalDate->format('d.m.Y') : $to->format('d.m.Y'),
		));
		$this->template->dateForm = $this['dateForm'];
	}
	
	public function renderSummary()
	{
		$articleService = new ArticleService($this->entityManager);
        $this->template->article = $articleService->buildArticle($this->trip->arrival, new ArticleWikipediaMapper())->text;
	}

    public function actionBooking($flightId)
    {
        try {
        	//@todo refactor (add access to trip)
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $depart_date = new DateTime;
            $return_date = new DateTime('+1 week');
            $flights = $flightService->buildFlights($flightMapper, 'PRG', 'PAR', $depart_date, $return_date, '1', 'e', 'n');

            $this->redirectUri("http://kayak.com" . $flights[$flightId - 1]->getBook());
        } catch (FlightException $e)
        {
            $this->flashMessage('Redirect failed', 'error');
        }
    }

}
