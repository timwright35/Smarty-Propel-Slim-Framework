<?php

class AppController {

  protected $view;
  protected $vars;
  protected $app;

	function __construct($app){
		$this->view = null;
		$this->vars = array();
    $this->app = $app;
	}
	
	public function getView(){
		return $this->view;
	}
	
	public function setView($view){
		$this->view = $view;
	}
	
	public function addVar($key,$val){
		$this->vars[$key] = $val;
	}
	
	public function getVar($key){
		return $this->vars[$key];
	}

  public function getVars(){
    return $this->vars;
  }

  function render(){
    $this->app->render('extends:app/layout.tpl|' . $this->getView(), $this->getVars());
  }
	
}