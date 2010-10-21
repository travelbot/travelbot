<?php

/**
 * Interface for getting trip directions via departure and arrival locations.
 * @author mirteond 
 */
interface ITripMapper
{

	function getTripDirections($departure, $arrival);

}
