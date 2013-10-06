<?php

class Ajax extends govOutSide {
	var $registered_classes, $user_id;
	
	function __construct() {
		$this->config = config();
	}
	
	//use this to check if this class is active already
	function check() {
		return true;	
	}
	
	function getOutput() {
		$data = $_GET;
		$result = array();
		$user = $this->registered_classes['Users']->user;
		if(isset($data['api_key'])){
			if($data['api_key'] !== $user['api_key']){
				$result = array( 'Status' => 'Error: Keys do not match...' );
			}else{
				//build out categories
				$categories = $this->registered_classes['System']->getCategories();
				print_r($this->registered_classes['System']->getCategories());
				$result['init']['zoom'] = 8;
				$result['init']['center']['lat'] = 8;
				$result['init']['center']['lng'] = 8;
	
				$result['categories']['slug'] = 8;
				$result['categories']['title'] = 8;
				$result['categories']['color'] = 8;
	
				$result['locations']['title'] = 8;
				$result['locations']['lat'] = 8;
				$result['locations']['lng'] = 8;
				$result['locations']['desc'] = 8;
				$result['locations']['category'] = 8;
			}
		}else{
			$result = array( 'Status' => 'Error: No api key given' );	
		}
		header('Content-Type: application/json');
		echo json_encode($result);
		die;
	}

	function renderAction($registered_classes) {		
		$action = 'getOutput';
		
		if(isset($_GET['action'])){
			$action = $_GET['action'];
		}
		$this->registered_classes = $registered_classes;
		$this->user_id = $this->registered_classes['Users']->isLoggedIn();
		return $this->$action();
	}

}