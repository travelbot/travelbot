<?phpuse Nette\Application\AppForm;use Nette\Application\JsonResponse;/** * Presenter for planning a new trip. * @author mirteond * */class HomepagePresenter extends BasePresenter {    /**     * @return Nette\Application\AppForm     */    protected function createComponentLocationsForm() {        $form = new AppForm;        $form->addText('from', 'From')                ->setRequired('Fill the "from" location, please.');        $form->addText('to', 'To')                ->setRequired('Fill the "to" location, please.');        $form->addSubmit('okSubmit', 'Save trip');        $form->addSubmit('okFindDirections', 'Find directions');        $form->onSubmit[] = array($this, 'submitLocationsForm');        return $form;    }    /**     * locationsForm submit handler (trip saving).     * @param Nette\Application\AppForm     */    public function submitLocationsForm(AppForm $form) {        $values = $form->values;        $service = new TripService($this->entityManager);        try {            $trip = $service->buildTrip($values['from'], $values['to'], new TripCurlMapper);            $service->save($trip);            $this->flashMessage('Trip successfully saved.');            $this->redirect('Trip:show', array('id' => $trip->id));        } catch (InvalidStateException $e) {            $form->addError('Error occurred while getting directions. Please try again later.');        }    }    /**     * AJAX signal handler for getting user location string.     */    public function handleLocation() {        $latitude = $this->request->post['latitude'];        $longitude = $this->request->post['longitude'];        $service = new LocationService;        try {            $location = $service->getLocation($latitude, $longitude);        } catch (InvalidStateException $e) {            $this->terminate(new JsonResponse(array(                        'status' => 'FAIL',                    )));        }        $this->terminate(new JsonResponse(array(                    'status' => 'OK',                    'location' => $location->street . ', ' . $location->city . ', ' . $location->country,                )));    }    /**     * AJAX signal handler for getting navigation directions info.     */    public function handleDirections() {        $from = $this->request->post['from'];        $to = $this->request->post['to'];        $service = new TripService($this->entityManager);        try {            $trip = $service->buildTrip($from, $to, new TripCurlMapper);        } catch (InvalidStateException $e) {            $this->terminate(new JsonResponse(array('status' => 'FAIL')));        }        $steps = array();        foreach ($trip->steps as $step) {            $arr = array();            $arr['distance'] = $step->distance;            $arr['instructions'] = $step->instructions;            $steps[] = $arr;        }        $this->terminate(new JsonResponse(array(                    'status' => 'OK',                    'duration' => $trip->duration,                    'distance' => $trip->distance,                    'steps' => $steps,                )));    }    /**     * @author Petr Vales     * @version 4.11.2010     */    public function handlePosisions() {        $lat = $this->request->post['latitude'];        $lng = $this->request->post['longitude'];        $service = new POIService($lat, $lng);        try {            $POIs = $service->getPOIs($lat, $lng);        } catch (InvalidStateException $e) {            $this->terminate(new JsonResponse(array(                        'status' => 'FAIL',                    )));        }        $jsonResponse = array();        foreach ($POIs as $POI) {               //'status' => 'OK'  ???????            $jsonResponse[] = array(                'name' => $POI->name,                'vicinity' => $POI->vicinity,                'types' => $POI->types,                'phoneNumber' => $POI->phoneNumber,                'address' => $POI->address,                'lat' => $POI->lat,                'lng' => $POI->lng,                'rating' => $POI->rating,                'url' => $POI->url,                'icon' => $POI->icon,                'reference' => $POI->reference            );        }        $this->terminate(new JsonResponse($jsonResponse));    }    public function renderDefault() {        $this->template->locationsForm = $this['locationsForm'];    }}