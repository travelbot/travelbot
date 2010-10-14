<?php

/**
 * Bootstrap file.
 */


use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

use Nella\Panels\Doctrine2Panel;

use Nette\Debug;
use Nette\Environment;
use Nette\Application\Route;
use Nette\Application\SimpleRouter;


// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';



// Step 2: Configure environment
// 2a) enable Nette\Debug for better exception and error visualisation
Debug::$strictMode = TRUE;
Debug::enable();


// 2b) load configuration from config.ini file
Environment::loadConfig();



// Step 3: Configure application
// 3a) get and setup a front controller
$application = Environment::getApplication();
$application->errorPresenter = 'Error';
//$application->catchExceptions = TRUE;



// Step 4: Setup application router
$router = $application->getRouter();

$router[] = new Route('index.php', array(
	'presenter' => 'Homepage',
	'action' => 'default',
), Route::ONE_WAY);

$router[] = new Route('<presenter>/<id [0-9]+>', array(
	'action' => 'show',
));

$router[] = new Route('<presenter>/<action>/<id>', array(
	'presenter' => 'Homepage',
	'action' => 'default',
	'id' => NULL,
));

// Doctrine configuration
$arrayCache = new ArrayCache;
$doctrineConfig = new Configuration;
$doctrineConfig->setMetadataCacheImpl($arrayCache);
$doctrineConfig->setQueryCacheImpl($arrayCache);
$doctrineConfig->setMetadataDriverImpl($doctrineConfig->newDefaultAnnotationDriver(array(
	APP_DIR . '/models',
)));
$doctrineConfig->setProxyNamespace('DoctrineProxy');
$doctrineConfig->setProxyDir(TEMP_DIR . '/cache');
//$doctrineConfig->setSQLLogger(Doctrine2Panel::getAndRegister());

$entityManager = EntityManager::create(Environment::getConfig('database')->toArray(), $doctrineConfig);
$application->getContext()->addService('Doctrine\ORM\EntityManager', $entityManager);

// Step 5: Run the application!
// Don't run while running PHPUnit tests
if (!Environment::isConsole()) {
	$application->run();
}
