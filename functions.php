<?php

require_once('config.php');

class govOutSide {
	var $users_core;
	var $config;
	var $messages = array();
	var $registered_classes = array();
	function __construct() {
		$this->config = config();
		//init functions like connect to database and running anything we need upon start
		$this->db_connect();
		//include classes
		$this->includeClasses();
	}
	
	private function db_connect() {
		$link = mysql_connect($this->config['db']['host'], $this->config['db']['username'], $this->config['db']['password']);
		if (!$link) {
			die('Could not connect: ' . mysql_error());
		}

		$db_selected = mysql_select_db($this->config['db']['name'], $link);
		if (!$db_selected) {
			die ('Mysql Error: ' . mysql_error());
		}	
	}
	
	public function getTemplates() {
		$output = array(
					0 => array(
						'page_title' => 'Home',
						'name' => 'index',
						'file' => 'index.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login', 'init' => true)),
						'login_required' => false
					),
					1 => array(
						'page_title' => 'Users',
						'name' => 'users',
						'file' => 'users.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login')),
						'login_required' => false
					),
					2 => array(
						'page_title' => 'System',
						'name' => 'system',
						'file' => 'system.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login')),
						'login_required' => true
					),
					
					//keep this one for error generation
					999 => array(
						'page_title' => 'Error',
						'name' => 'error',
						'file' => 'error.php',
						'before' => array(0 => 'includes/header.php'),
						'after' => array(0 => 'includes/footer.php'),
						'classes' => array( 0 => array('type'=>'login')),
						'login_required' => false
					)
				);
		return $output;
	}
	
	public function getClasses() {
		$output = array(
					0 => array(
						'name' => 'Users',
						'file' => 'users.php'
					),
					1 => array(
						'name' => 'System',
						'file' => 'system.php'
					)
				);
		return $output;
	}
	
	public function formOutput($form_fields){
		$output = '';
		$i=0;
		foreach($form_fields as $field){
			$value = '';
			if(isset($field['value'])){
				$value = $field['value'];	
			}
			$customClass = '';
			if(isset($field['class'])){
				$customClass = $field['class'];	
			}
				if($field['type']!=='textarea' || $field['type']!=='select'){
					$output.= '<div class="input-container">';
						$output.= '<label for="'.$field['name'].'">'.$field['label'];
						if($field['required']==true) $output.= '<div class="required_field">*</div>';
						$output.= '</label>';
						$output.= '<input type="'.$field['type'].'" name="'.$field['name'].'" value="'.$value.'" id="field_'.$i.'" class="form_'.$field['type'].' '.$customClass.' input" />';
					$output.= '</div>';
				}
				if($field['type']=='select'){
					$output.= '<div class="input-container">';
						$output.= '<label for="'.$field['name'].'">'.$field['label'];
						if($field['required']==true) $output.= '<div class="required_field">*</div>';
						$output.= '</label>';
						$output.= '<select name="'.$field['name'].'" id="field_'.$i.'" class="form_'.$field['type'].' '.$customClass.' input">';
							$output.= '<option value="0">'.$field['first_option'].'</option>';
							if(count($field['options'])){
								foreach($field['options'] as $option){
									$output.= '<option value="'.$option['catid'].'">'.$option['label'].'</option>';	
								}
							}
						$output.= '</select>';
					$output.= '</div>';
				}
			$i++;
		}	
		return $output;
	}
	
	public function isLoggedIn() { // also could be called getUserId
		if(!empty($_SESSION['user']['uid'])){
			return $_SESSION['user']['uid'];
		}else{
			return false;	
		}
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
	
	public function handleMessages(){
		$messages = $_SESSION['message'];
		if(!empty($messages)){
			echo '<div class="system_message">';
				$i=0;
				foreach($messages as $message){
					echo '<div class="message" id="message_'.$i.'">';
						echo $message;
					echo '</div>';
					$i++;
				}
			echo '</div>';	
		}
		
		$_SESSION['message'] = '';
	}
	
	public function renderTemplate($templateInfo) {
		$classes = $this->getClasses();
		foreach($classes as $class){
			$methodVariable = array(&$registered_classes[$class['name']], 'check');
			if(!is_callable($methodVariable)) {
				$this->registered_classes[$class['name']] = $this->registerBaseClasses($class['name']);
			}
		}
		//does the template require a login?
		$is_logged = $this->isLoggedIn();
		if($templateInfo['login_required']==true){
			if($is_logged == false){
				die('<script> window.location = window.location; </script>');
			}
		}else{
			if($is_logged == true && $templateInfo['name']!=='error'){
				die('<script> window.location = "'.$this->config['base_url'].'?view=system"; </script>');
			}
		}
		
		//first include the main template
		if(isset($templateInfo['before'])){
			foreach($templateInfo['before'] as $before){
				include('templates/'.$before);
			}
		}
		
		// handle all session messages
		if(!empty($_SESSION['message'])){
			$this->messages = $this->handleMessages();
		}
		
		include('templates/'.$templateInfo['file']);
		
		if(isset($templateInfo['after'])){
			foreach($templateInfo['after'] as $after){
				include('templates/'.$after);
			}
		}
	}
	
}
