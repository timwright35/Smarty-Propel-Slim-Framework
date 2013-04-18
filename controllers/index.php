<?php
require_once(dirname(dirname(__FILE__)) . '/configs/config.inc.php');

$smartyView = new \Slim\Extras\Views\Smarty();
$smartyView->setSmartyDirectory(BASE_DIR . '/vendor/smarty/smarty/distribution/libs');
$smartyView->setSmartyCompileDirectory(BASE_DIR . '/tmp/templates_c');
$smartyView->setSmartyCacheDirectory(BASE_DIR . '/tmp/cache');
$smartyView->setSmartyTemplateDirectory(BASE_DIR . '/templates');

$app = new \Slim\Slim(array(
	'view' => $smartyView,
	'debug' => true
));

//TODO: Make function to serach for controllers without require once

$app->get('/', function() use ($app){
	require_once(BASE_DIR . '/controllers/main.php');
	
	$main = new Main();
	$main->setView('pages/main.tpl');

	$app->render($main->getView());
});

$app->run();
