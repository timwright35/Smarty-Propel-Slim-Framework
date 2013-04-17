<?php

class controller {

	private static $view = null;
	private static $vars = array();

	function __construct(){}
	
	public function getView(){
		return $this->view;
	}
	
	public function setView($view){
		$this->view = $view;
	}
	
	public function setVars($vars){
		$this->vars = $vars;
	}
	
	public function addVar($var){
		$this->vars[] = $var;
	}
	
	public function getVar($key){
		return $this->vars[$key];
	}
	
}