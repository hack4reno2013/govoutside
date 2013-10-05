<?php

require_once('config.php');

class govOutSide {
	var $users_core;
	function __construct() {
		//init functions like connect to database and running anything we need upon start
		$this->db_connect();
		//include classes
		$this->includeClasses();
	}
	
	private function db_connect() {
		$config = config();
		
		$link = mysql_connect($config['db']['host'], $config['db']['username'], $config['db']['password'] = '');
		if (!$link) {
			die('Could not connect: ' . mysql_error());
		}
		
		$db_selected = mysql_select_db('govoutside', $link);
		if (!$db_selected) {
			die ('Mysql Error: ' . mysql_error());
		}	
	}
	
	public function getTemplates() {
		$output = array(
					0 => array(
						'name' => 'index',
						'file' => 'index.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login', 'init' => true))
					),
					1 => array(
						'name' => 'users',
						'file' => 'users.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login', 'init' => false))
					),
					
					//keep this one for error generation
					999 => array(
						'name' => 'error',
						'file' => 'error.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login', 'init' => true))
					)
				);
		return $output;
	}
	
	public function getClasses() {
		$output = array(
					0 => array(
						'name' => 'Users',
						'file' => 'users.php',
						'checkFunction' => 'login'
					)
				);
		return $output;
	}
	
	public function includeClasses() {
		$classes = $this->getClasses();
		$i=0;
		foreach($classes as $class){
			include_once('classes/'.$class['file']);	
		}
	}
	
	public function checkTemplate($request) {
		$templates = $this->getTemplates();
		$i=0;
		foreach($templates as $template){
			if($request == $template['name']){
				return $i;	
			}
			$i++;
		}
		
		return -1;
	}
	
	private function registerBaseClasses($type) {
		return new $type();	
	}
	
	
	public function renderTemplate($templateInfo) {
		$classes = $this->getClasses();
		$registered_classes = array();
		foreach($classes as $class){
			$methodVariable = array(&$registered_classes[$class['name']], $class['checkFunction']);
			if(!is_callable($methodVariable)) {
				$registered_classes[$class['name']] = $this->registerBaseClasses($class['name']);
			}
		}
		//first include the main template
		if(isset($templateInfo['before'])){
			foreach($templateInfo['before'] as $before){
				include('templates/'.$before);
			}
		}
		
		include('templates/'.$templateInfo['file']);
		
		if(isset($templateInfo['after'])){
			foreach($templateInfo['after'] as $after){
				include('templates/'.$after);
			}
		}
	}
	
}
