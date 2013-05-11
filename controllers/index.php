<?php
require_once(dirname(dirname(__FILE__)) . '/configs/config.inc.php');
require_once(dirname(dirname(__FILE__)) . "/extras/Smarty.php");

function autoload($className) {
  $file_path = BASE_DIR . '/controllers/' . $className . '.php';
  if(file_exists($file_path)){
    require_once($file_path);
  }
}
spl_autoload_register('autoload');

$smartyView = new \Slim\Extras\Views\Smarty();
$smartyView->setSmartyDirectory(BASE_DIR . '/vendor/smarty/smarty/distribution/libs');
$smartyView->setSmartyCompileDirectory(BASE_DIR . '/tmp/templates_c');
$smartyView->setSmartyCacheDirectory(BASE_DIR . '/tmp/cache');
$smartyView->setSmartyTemplateDirectory(BASE_DIR . '/templates');

$app = new \Slim\Slim(array(
	'view' => $smartyView,
	'debug' => true //Change before production
));

$app->get('/', function () use ($app) {
  $main = new MainController($app);
  if (isset($_SESSION['user'])) {
    $main->index();
  } else {
    $app->redirect('/login');
  }
});

$app->map('/login', function() use ($app){
  $auth = new AuthController($app);
  if($app->request()->isPost()){
    $auth->login();
  }else{
    $auth->index();
  }
})->via('GET','POST');

$app->get('/logout', function() use ($app){
  $_SESSION['user'] = null;
  $app->redirect('/');
});

//Sample on how to use map resources
//map_resource($app, 'users','UsersController');

$app->run();

function map_resource($app,$route,$controller_name){
  $controller = new $controller_name($app);

  $app->map('/' . $route . "/?", function() use ($app, $controller){
    if($app->request()->isPost()){
      $controller->create($app->router()->getCurrentRoute()->getParams());
    }else{
      $controller->index($app->router()->getCurrentRoute()->getParams());
    }
  })->via('GET','POST');

  $app->get('/'. $route . '/new/?', function() use ($app, $controller){
    $controller->add($app->router()->getCurrentRoute()->getParams());
  });

  $app->get('/' . $route . '/:id/edit/?', function() use ($app, $controller){
    $controller->edit($app->router()->getCurrentRoute()->getParams());
  });

  $app->map('/' . $route . '/:id/?', function() use ($app, $controller){
    if($app->request()->isPost() || $app->request()->isPut()){
      $controller->update($app->router()->getCurrentRoute()->getParams());
    }else if($app->request()->isGet()){
      $controller->show($app->router()->getCurrentRoute()->getParams());
    }else if($app-request()->isDelete()){
      $controller->delete($app->router()->getCurrentRoute()->getParams());
    }
  })->via('POST','GET','PUT','DELETE');

  $app->get('/' . $route . '/:id/delete/?', function() use ($app, $controller){
    $controller->delete($app->router()->getCurrentRoute()->getParams());
  });
}