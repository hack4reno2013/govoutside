<?php

class Users extends govOutSide {
	var $user = array();
	var $config = '';
	function __construct() {
		$this->config = config();
		//lets check if they are logged in if so then set the global users array as the log data
		if(!empty($_SESSION['user'])){
			$this->user = $_SESSION['user'];				
		}
		
	}
	
	//use this to check if this class is active already
	function check() {
		return true;	
	}
	
	function getFormFields($type){
		switch($type){
			case 'register':
				return array(
					0 => array( 'label' => 'Organization', 'name' => 'org', 'type' => 'text', 'required' => true ),
					1 => array( 'label' => 'First Name', 'name' => 'first_name', 'type' => 'text', 'required' => true ),
					2 => array( 'label' => 'Last Name', 'name' => 'last_name', 'type' => 'text', 'required' => true ),
					3 => array( 'label' => 'E-mail', 'name' => 'email', 'type' => 'text', 'required' => true ),
					4 => array( 'label' => 'Password', 'name' => 'password', 'type' => 'password', 'required' => true ),
					5 => array( 'label' => 'Confirm Password', 'name' => 'confirm_password', 'type' => 'password', 'required' => true )
				);
			break;	
			case 'login':
				return array(
					0 => array( 'label' => 'E-mail', 'name' => 'email', 'type' => 'text', 'required' => true ),
					1 => array( 'label' => 'Password', 'name' => 'password', 'type' => 'password', 'required' => true )
				);
			break;
		}
	}
		
	function renderAction() {
		//check if they are logged in and route them out of here!
		if(!empty($this->user['uid'])){
			$_SESSION['message'][] = '<a href="'.$this->config['base_url'].'?view=users&action=logout">You are logged in already. You can logout by clicking this message</a>';
			die('<script> window.location = window.location; </script>');
		}
		
		$action = 'login';
		if(isset($_GET['action'])){
			$action = $_GET['action'];
		}
		return $this->$action();
	}
	
	function login() {
		if(!empty($_POST)) {
			$loginResults = $this->handleLogin();
			
		}
		$output = '';
			if(isset($loginResults['error'])){
				$output.= $this->handleError($loginResults);
			}
		
		$output.= $this->login_form();
		
		return $output;
			
	}
	
	function login_form() {
			$output = '<form id="register_form" name="register_form" method="post">';
			$output.= '<div class="form_container" id="users-register">';
				$form_fields = $this->getFormFields('login');
				$fieldsCount = count($form_fields);
				if($fieldsCount > 0){
					$output.= parent::formOutput($form_fields);
				}
			$output.= '<input type="submit" class="submit" value="Submit" />';
			$output.= '</div><div class="register_button"><a href="'.$this->config['base_url'].'?view=users&action=register">Register an Account</a></div></form>';
		return $output;
	}
	
	function handleLogin() {
		$data = $_POST;
		if(!empty($data['email']) && !empty($data['password'])){
			$email = $data['email'];
			$password = md5($data['password']);
			$query = 'SELECT * FROM users WHERE `email` = "'.$email.'" and password = "'.$password.'"';
			$results = mysql_query($query)or die(mysql_error());
			$data = mysql_fetch_assoc($results);
			
			if(isset($data['uid'])){
				$this->user = $data;
				// start login process.
				$_SESSION['user']['uid'] = $this->user['uid'];
				$_SESSION['user']['email'] = $this->user['email'];
				$_SESSION['message'][] = '<a href="'.$this->config['base_url'].'?view=users&action=logout">Thank you for logging in! You can logout by clicking this message if you want...</a>';
				die('<script> window.location = window.location; </script>');
			}
		}else{
			return array( 'error' => array( 'You left a field blank...') );	
		}
	}
	
	function handleError($data) {
		$output = '';
		if(isset($data['error'])){
			foreach($data['error'] as $error) {
				$output.= '<p class="error">'.$error.'</p>';	
			}
			
			return $output;
		}
		
		return false;
	}
	
	function register() {
		
		if(!empty($_POST)){
			$handle_register = $this->handleRegister();
		}
			$output = '<form id="register_form" name="register_form" method="post">';
			if(isset($handle_register['error'])){
				$output.= $this->handleError($handle_register);
			}
			$output.= '<div class="form_container" id="users-register">';
				$form_fields = $this->getFormFields('register');
				$fieldsCount = count($form_fields);
				if($fieldsCount > 0){
					$output.= parent::formOutput($form_fields);
				}
			$output.= '<input type="submit" class="submit" value="Submit" />';
			$output.= '</div><div class="register_button"><a href="'.$this->config['base_url'].'?view=users&action=login">Login</a></div></form>';
		return $output;
		
	}
	
	function logout() {
				$_SESSION['user'] = '';
				$_SESSION['message'][] = '<a href="'.$this->config['base_url'].'?view=users&action=logout">You have now logged out! Please come back soon.</a>';
				die('<script> window.location = window.location; </script>');
	}
	
	function checkIsEmail($requestEmail) {
		$query = 'SELECT * FROM users where `email` = "'.$requestEmail.'"';
		$results = mysql_query($query)or die(mysql_error());
		$rows = mysql_fetch_array($results);
		
		if(!empty($rows)){
			return false;	
		}
		
		return true;
	}
	
	function apiGeneration($data) {
		return base64_encode($data['email'].':'.$data['first_name'].'__'.$data['last_name'].':'.rand(999,9999));	
	}
	
	function handleRegister() {
		$data = $_POST;
		//validate
		$validate = $this->validateForm($data,'register');
		if(isset($validate['error'])){
			return $validate;	
		}
		
		//lets check if the user email is already in the database
		$checkEmail = $this->checkIsEmail($data['email']);
		if($checkEmail == false){
			return array( 'error' => array('That email is already registered!'));
		}
		// create api_key
		$data['api_key'] = $this->apiGeneration($data);
		//save data
		$query = 'INSERT INTO users (org,first_name,last_name,email,password,active,api_key) VALUES (
			"'.$data['org'].'",
			"'.$data['first_name'].'",
			"'.$data['last_name'].'",
			"'.$data['email'].'",
			"'.md5($data['password']).'",
			"1",
			"'.$data['api_key'].'"
		)';
		
		$results = mysql_query($query)or die(mysql_error());
		die('<script> window.location = "'. $this->config['base_url'].'?view=users&action=login"; </script>');
		
	}
	
	function validateForm($data, $type) {
		$form_fields = $this->getFormFields($type);
		$returnArray = '';
		//validate form fields
		foreach($data as $key=>$value){
			foreach($form_fields as $field){
				if($field['name'] == $key){
					if($value=='' && $field['required']==true){
						$returnArray['error'][] = 'You need to fill out the '.ucwords($field['label']).' field!';	
					}
					if($field['name']=='confirm_password'){
						if($data['confirm_password'] !== $data['password']){
							$returnArray['error'][] = 'Your passwords do not match. Please confirm the correct password!';
						}
					}
				}
			}
		}
		
		return $returnArray;
	}
	
}

