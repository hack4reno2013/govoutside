<?php

class System extends govOutSide {
	
	function __construct() {
		
	}
	
	//use this to check if this class is active already
	function check() {
		return true;	
	}
	
	function renderAction() {		
		$action = 'dashboard';
		if(isset($_GET['action'])){
			$action = $_GET['action'];
		}
		return $this->$action();
	}
	
	function dashboard() {
		
		include('/templates/sub_templates/system/dashboard.php');
	}

}