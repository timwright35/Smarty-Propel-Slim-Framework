<?php

class MainController extends AppController {

	public function index(){
    $this->setView('pages/main.tpl');
    $this->render();
  }
}