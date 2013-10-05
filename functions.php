<?php

require_once('config.php');

class govOutSide {
	
	function __construct() {
		//init functions like connect to database and running anything we need upon start
		$this->db_connect();
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
						'after' => array(0 => 'includes/footer.php')
					),
					1 => array(
						'name' => 'users',
						'file' => 'users.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php')
					),
					
					//keep this one for error generation
					999 => array(
						'name' => 'error',
						'file' => 'error.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php')
					)
				);
		return $output;
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
	
	public function renderTemplate($templateInfo) {
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
