<?php

class MockTripMapper extends Nette\Object implements ITripMapper
{

	public function getTripDirections($departure, $arrival)
	{
		return (object) array(
			'routes' => array((object) array('legs' => array((object) array(
				'steps' => array(
					(object) array(
						'distance' => (object) array(
							'value' => '1',
						),
						'duration' => (object) array(
							'value' => '2',
						),
						'html_instructions' => 'text',
						'polyline' => (object) array(
							'points' => 'poly',
						),
					),
					(object) array(
						'distance' => (object) array(
							'value' => '1',
						),
						'duration' => (object) array(
							'value' => '2',
						),
						'html_instructions' => 'text',
						'polyline' => (object) array(
							'points' => 'poly',
						),
					),
					(object) array(
						'distance' => (object) array(
							'value' => '1',
						),
						'duration' => (object) array(
							'value' => '2',
						),
						'html_instructions' => 'text',
						'polyline' => (object) array(
							'points' => 'poly',
						),
					),
				)
			)))));
	}

}
