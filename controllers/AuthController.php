<?php

class AuthController extends AppController {

  public function index(){
      $this->setView('pages/login.tpl');
      $this->addVar('username', null);
      $this->addVar("error", null);
      $this->render();
  }

  function login(){
    $post = $this->app->request()->post();
    $user = UserQuery::create()->filterByActive(1)->findOneByUsername($post['username']);

    if(!$user){
      $this->setView('pages/login.tpl');
      $this->addVar('username', $post['username']);
      $this->addVar("error","Invalid Login");
      $this->render();
      return;
    }

    if(!$user->match_password($post['password'])){
      $this->setView('pages/login.tpl');
      $this->addVar('username', $post['username']);
      $this->addVar("error","Invalid Login");
      $this->render();
      return;
    }

    $_SESSION['user'] = $user;
    $this->app->redirect('/');
  }

}