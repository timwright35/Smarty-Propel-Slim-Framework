<?php

class controller {

	private static $view;
	private static $vars;

	function __construct(){
		$this->view = null;
		$this->vars = array();
	}
	
	public function getView(){
		return $this->view . "," . $this->vars;
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
	
	public function processPost($post){
	
	}
	
	public function processGet($get){
	
	}
	
	public function processPut($put){
	
	}
	
	public function processDelete($delete){
	
	}
	
	public function processOptions($options){
	
	}
	
}