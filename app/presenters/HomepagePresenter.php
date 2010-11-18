<?php

use Nette\Application\AppForm;

/**
 * Presenter for planning a new trip.
 * @author mirteond
 *
 */
class HomepagePresenter extends BasePresenter {

    /**
     * @return Nette\Application\AppForm
     */
    protected function createComponentLocationsForm() {
        $form = new AppForm;

        $form->addText('from', 'From')
                ->setRequired('Fill the "from" location, please.');

        $form->addText('to', 'To')
                ->setRequired('Fill the "to" location, please.');

        $form->addRadioList('oneWay', null, array('One way', 'Round trip'));

        $form->addText('depart', 'Depart');
        $form->addText('return', 'Return');

        $form->addSelect('travelers', 'How many travelers', array('1 traveler',
                                                                  '2 travelers',
                                                                  '3 travelers',
                                                                  '4 travelers',
                                                                  '5 travelers',
                                                                  '6 travelers',
                                                                  '7 travelers',
                                                                  '8 travelers'));

        $form->addSelect('cabin', 'Cabin', array('Economy',
                                                  'Premium economy',
                                                  'Business',
                                                  'First'));

        $form->addSubmit('okSubmit', 'Save trip')
        	->setDisabled();
        $form->addSubmit('okFindDirections', 'Find route');
        $form->onSubmit[] = array($this, 'submitLocationsForm');

        return $form;
    }

    /**
     * locationsForm submit handler (trip saving).
     * @param Nette\Application\AppForm
     */
    public function submitLocationsForm(AppForm $form) {
        $values = $form->values;

        $service = new TripService($this->entityManager);
        try {
            $trip = $service->buildTrip($values['from'], $values['to'], new TripCurlMapper);
            $service->save($trip);
            $this->flashMessage('Trip successfully saved.');
            $this->redirect('Trip:show', array('id' => $trip->id));
        } catch (InvalidStateException $e) {
            $form->addError('Error occurred while getting directions. Please try again later.');
        }
    }

    public function renderDefault() {
        $this->template->locationsForm = $this['locationsForm'];
    }

}
