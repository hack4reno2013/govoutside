<?php

class Users extends govOutSide {
	var $user = array();
	
	function __construct() {
		session_start();
		//lets check if they are logged in if so then set the global users array as the log data
		
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
					0 => array( 'label' => 'E-mail', 'name' => 'email', 'type' => 'text' ),
					1 => array( 'label' => 'Password', 'name' => 'password', 'type' => 'password' )
				);
			break;
		}
	}
	
	function formOutput($form_fields){
		$output = '';
		foreach($form_fields as $field){
					$output.= '<div class="input-container">';
						$output.= '<label for="'.$field['name'].'">'.$field['label'];
						if($field['required']==true) $output.= '<div class="required_field">*</div>';
						$output.= '</label>';
						$output.= '<input type="'.$field['type'].'" name="'.$field['name'].'" value="" class="form_'.$field['type'].' input" />';
					$output.= '</div>';
		}	
		return $output;
	}
	
	function renderAction() {
		$action = 'login';
		if(isset($_GET['action'])){
			$action = $_GET['action'];
			return $this->$action();
		}
	}
	
	function login() {
		if(empty($user)){
			$output = '<form id="register_form" name="register_form" method="post">';
			$output.= '<div class="form_container" id="users-register">';
				$form_fields = $this->getFormFields('login');
				$fieldsCount = count($form_fields);
				if($fieldsCount > 0){
					$output.= $this->formOutput($form_fields);
				}
			$output.= '<input type="submit" class="submit" value="Submit" />';
			$output.= '</div></form>';
		}else{
			$output = $this->register();	
		}
		return $output;
	}
	
	function handleLogin() {
		
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
					$output.= $this->formOutput($form_fields);
				}
			$output.= '<input type="submit" class="submit" value="Submit" />';
			$output.= '</div></form>';
		return $output;
		
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
			return array( 'That email is already registered!');
		}
		
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

