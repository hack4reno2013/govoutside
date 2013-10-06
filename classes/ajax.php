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
	
	function getCategories() {
		$query = 'SELECT * FROM categories WHERE `uid` = "'.$this->user_id.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		while ($row = mysql_fetch_assoc($results)){
			$data[] = $row;
		}
		if(!isset($data[0])){
			return false;	
		}
		
		return $data;
	}
	
	function getLocations() {
		$query = 'SELECT * FROM locations l LEFT JOIN categories c ON l.catid = c.catid WHERE l.uid = "'.$this->user_id.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		while ($row = mysql_fetch_assoc($results)){
			$data[] = $row;
		}
		if(!isset($data[0])){
			return false;	
		}
		
		return $data;
	}
	
	function findUserbyKey($key) {
		$query = 'SELECT * FROM users WHERE api_key = "'.$key.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		while ($row = mysql_fetch_assoc($results)){
			$data[] = $row;
		}
		if(!isset($data[0])){
			return false;	
		}
		$this->user_id = $data[0]['uid'];
		return $data;
	}

	function getOutput() {
		$data = $_GET;
		$result = array();
		$user = $this->registered_classes['Users']->user;
		if(isset($data['api_key'])){
			$check_api_key = $this->findUserbyKey($data['api_key']);
			if($check_api_key==false){
				$result = array( 'Status' => 'Error: Keys do not match...' );
			}else{
				//build out categories
				$categories = $this->getCategories();
				$locations = $this->getLocations();
				
				$result['init']['zoom'] = 8;
				if($categories && count($categories)>0){
					$i = 0;
					foreach($categories as $category){
						$result['categories'][$i]['slug'] = str_replace(array(' ',''),array('_','-'), $category['label']);
						$result['categories'][$i]['title'] = $category['label'];
						$result['categories'][$i]['color'] = $category['color'];
						$i++;
					}
				}
				else {
					$result['categories'] = array();
				}
				
				if($locations && count($locations)>0){
					$i=0;
					$average_lat = 0;
					$average_lng = 0;
					foreach($locations as $location){
						$result['locations'][$i]['title'] = $location['name'];
						$result['locations'][$i]['lat'] = $location['lat'];
						$average_lat = $location['lat'] + $average_lat;
						$result['locations'][$i]['lng'] = $location['lon'];
						$average_lng = $location['lon'] + $average_lng;
						$result['locations'][$i]['desc'] = $location['address'];
						$result['locations'][$i]['category'] = $location['label'];
						$i++;
					}
				$result['init']['center']['lat'] = $average_lat/count($locations);
				$result['init']['center']['lng'] = $average_lng/count($locations);
				}
				else {
					$result['locations'] = array();
				}
			}
		}else{
			$result = array( 'Status' => 'Error: No api key given' );	
		}
		header('Access-Control-Allow-Origin: *');
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
		//$this->user_id = $this->registered_classes['Users']->isLoggedIn();
		return $this->$action();
	}

}