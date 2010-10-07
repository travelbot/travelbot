<?php

/** Set up application via its bootstrap */
require_once(__DIR__ . '/../public/index.php');

/** Load base test class */
require_once(__DIR__ . '/TestCase.php');

/** Include PHPUnit framework */
if (!class_exists('PHPUnit_Framework_TestCase')) {
	require_once('PHPUnit/Framework.php');
}
