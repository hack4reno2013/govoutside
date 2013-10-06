<?php

class System extends govOutSide {
	var $registered_classes, $user_id;
	function __construct() {
		$this->config = config();
	}
	
	//use this to check if this class is active already
	function check() {
		return true;	
	}
	
	function getCategories() {
		$query = 'SELECT * FROM categories WHERE `uid` = "'.$this->user_id.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		$data = mysql_fetch_assoc($results);
		
		return $data;
	}
	
	function logout() {
		print_r($registered_classes);
	}
	
	function getFormFields($type){
		
		switch($type){
			case 'location':
				$categories = $this->getCategories();
				die();
				return array(
					0 => array( 'label' => '', 'name' => 'uid', 'type' => 'hidden', 'value' => $this->user_id, 'required' => false ),
					1 => array( 'label' => '', 'name' => 'parentid', 'type' => 'hidden', 'value' => '0', 'required' => false ),
					2 => array( 'label' => 'Category', 'name' => 'catid', 'type' => 'select', 'options'=>$categories, 'value' => '', 'required' => true ),
					3 => array( 'label' => 'Icon', 'name' => 'iconid', 'type' => 'select', 'value' => '', 'required' => true ),
					4 => array( 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'value' => '', 'required' => true ),
					5 => array( 'label' => 'Address', 'name' => 'address', 'type' => 'text', 'value' => '', 'required' => true ),
					6 => array( 'label' => 'City', 'name' => 'city', 'type' => 'text', 'value' => '', 'required' => true ),
					7 => array( 'label' => 'State', 'name' => 'state', 'type' => 'text', 'value' => '', 'required' => true ),
					8 => array( 'label' => 'Zip Code', 'name' => 'zip', 'type' => 'text', 'value' => '', 'required' => true ),
					9 => array( 'label' => 'Lat (will be hidden)', 'name' => 'lat', 'type' => 'text', 'value' => '', 'required' => true ),
					10 => array( 'label' => 'Long (will be hidden)', 'name' => 'lon', 'type' => 'text', 'value' => '', 'required' => true ),
					11 => array( 'label' => '', 'name' => 'type', 'type' => 'hidden', 'value' => '', 'required' => false ),
					12 => array( 'label' => '', 'name' => 'active', 'type' => 'hidden', 'value' => '', 'required' => false ),
					13 => array( 'label' => '', 'name' => '', 'type' => 'text', 'value' => '', 'required' => false ),
				);
			break;	
			case 'categories':
				return array(
					0 => array( 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'required' => true ),
					1 => array( 'label' => 'Color', 'name' => 'color', 'type' => 'text', 'required' => true )
				);
			break;
		}
	}
	
	function formMediator($form_fields){
		return parent::formOutput($form_fields);
	}

	function renderAction($registered_classes) {		
		$action = 'dashboard';
		if(isset($_GET['action'])){
			$action = $_GET['action'];
		}
		$this->registered_classes = $registered_classes;
		$this->user_id = $this->registered_classes['Users']->isLoggedIn();
		return $this->$action();
	}
	
	function dashboard() {
		include('/templates/sub_templates/system/dashboard.php');
	}
	
	function handleInput() {
		
	}

}