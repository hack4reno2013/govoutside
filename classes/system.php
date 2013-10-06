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
		
		while ($row = mysql_fetch_assoc($results)){
			$data[] = $row;
		}
		if(!isset($data[0])){
			return false;	
		}
		
		return $data;
	}
	
	function getCategory($id) {
		$query = 'SELECT * FROM categories WHERE `catid` = "'.$id.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		$data = mysql_fetch_assoc($results);
			
		if(empty($data)){
			return false;	
		}
		
		return $data;
	}
	
	function getLocations() {
		$query = 'SELECT a.*, b.label, b.color FROM locations as a LEFT JOIN categories as b USING(catid) WHERE a.`uid` = "'.$this->user_id.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		while ($row = mysql_fetch_assoc($results)){
			$data[] = $row;
		}
		if(!isset($data[0])){
			return false;	
		}
		
		return $data;
	}
	
	function getLocation($id) {
		$query = 'SELECT * FROM locations WHERE `lid` = "'.$id.'"';
		$results = mysql_query($query)or die(mysql_error());
		
		$data = mysql_fetch_assoc($results);
			
		if(empty($data)){
			return false;	
		}
		
		return $data;
	}
	
	function logout() {
		$this->registered_classes['Users']->logout();
	}
	
	function getFormFields($type){
		$cur_id = 0;
		$cur_data = array();
		switch($type){
			case 'location':
				if(isset($_GET['id']) && isset($_GET['type'])) {
					if($_GET['type']=='location'){
					 $cur_id = $_GET['id'];
					 $cur_data = $this->getLocation($cur_id);
					}
				}
				$categories = $this->getCategories();
				return array(
					0 => array( 'label' => false, 'name' => 'uid', 'type' => 'hidden', 'value' => $this->user_id, 'required' => false ),
					1 => array( 'label' => false, 'name' => 'parentid', 'type' => 'hidden', 'value' => '0', 'required' => false ),
					2 => array( 'label' => 'Category', 'name' => 'catid', 'type' => 'select', 'first_option'=>'Select a Category', 'options'=>$categories, 'value' => @$cur_data['label'], 'required' => true ),
					3 => array( 'label' => 'Icon', 'name' => 'iconid', 'type' => 'select', 'first_option' => 'Select an Icon', 'options' => array(), 'value'=>@$cur_data['label'], 'required' => true ),
					4 => array( 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'value' => @$cur_data['name'], 'required' => true ),
					5 => array( 'label' => 'Address', 'name' => 'address', 'id'=>'address-input', 'type' => 'text', 'value' => @$cur_data['address'], 'required' => true ),
					//6 => array( 'label' => 'City', 'name' => 'city', 'type' => 'text', 'value' => @$cur_data['city'], 'required' => true ),
					//7 => array( 'label' => 'State', 'name' => 'state', 'type' => 'text', 'value' => @$cur_data['state'], 'required' => true ),
					//8 => array( 'label' => 'Zip Code', 'name' => 'zip', 'type' => 'text', 'value' => @$cur_data['zip'], 'required' => true ),
					9 => array( 'label' => 'Lat', 'name' => 'lat', 'class'=>'rwmb-map-latitude', 'type' => 'text', 'value' => @$cur_data['lat'], 'required' => true ),
					10 => array( 'label' => 'Long', 'name' => 'lon', 'class'=>'rwmb-map-longitude', 'type' => 'text', 'value' => @$cur_data['lon'], 'required' => true ),
					11 => array( 'label' => false, 'name' => 'type', 'type' => 'hidden', 'value' => @$cur_data['type'], 'required' => false ),
					12 => array( 'label' => false, 'name' => 'active', 'type' => 'hidden', 'value' => '1', 'required' => false ),
					13 => array( 'label' => false, 'name' => 'lid', 'type' => 'hidden', 'value' => $cur_id, 'required' => false )
				);
			break;	
			case 'categories':
				if(isset($_GET['id']) && isset($_GET['type'])) {
					if($_GET['type']=='categories'){
					 $cur_id = $_GET['id'];
					 $cur_data = $this->getCategory($cur_id);
					}
				}
				return array(
					0 => array( 'label' => false, 'name' => 'uid', 'type' => 'hidden', 'value' => $this->user_id, 'required' => false ),
					1 => array( 'label' => 'Label', 'name' => 'label', 'value' => @$cur_data['label'], 'type' => 'text', 'required' => true ),
					2 => array( 'label' => 'Color', 'name' => 'color', 'id'=>'color', 'value' => @$cur_data['color'], 'type' => 'color', 'required' => true ),
					3 => array( 'label' => false, 'name' => 'catid', 'type' => 'hidden', 'value' => $cur_id, 'required' => true )
				);
			break;
		}
	}
	
	function formMediator($form_fields, $type){
		$output = '<form id="register_form" name="'.$type.'_form" method="post">';
		//$output.= '<h2>'.ucwords($type).'</h2>';
		$output.= '<div class="form_container" id="users-register">';
		
			$output.= parent::formOutput($form_fields);
			if($type=='location')
			$output.= '<button class="button rwmb-map-goto-address-button" type="button" value="address-input">Find Address</button>';
		$output.= '<input type="hidden" name="type" class="type" value="'.$type.'" />';
		$output.= '<input type="submit" class="submit" value="Submit" />';
		$output.= '</div></form>';
		return $output;
	}

	function renderAction($registered_classes) {		
		$action = 'dashboard';
		if(!empty($_POST)){
			$data = $_POST;
			
			switch($data['type']){
				case 'categories':
					$data['catid'] = 0;
					$results = $this->handleCategoryInput($data);
				break;
				case 'location':
					$results = $this->handleLocationInput($data);
				break;
			}
		}
		
		if(isset($_GET['action'])){
			$action = $_GET['action'];
		}
		$this->registered_classes = $registered_classes;
		$this->user_id = $this->registered_classes['Users']->isLoggedIn();
		return $this->$action();
	}
	
	function dashboard() {
		include(dirname(__FILE__) . '/../templates/sub_templates/system/dashboard.php');
	}
	
	function handleCategoryInput($data) {
		$query = 'REPLACE INTO categories (catid, uid,label,color) VALUES (
			"'.$data['catid'].'",
			"'.$data['uid'].'",
			"'.$data['label'].'",
			"'.$data['color'].'"
		)';
		
		$results = mysql_query($query)or die(mysql_error());
	}

	function handleLocationInput($data) {
		$query = 'REPLACE INTO locations (lid,uid,catid,iconid,parentid,name,address,city,state,zip,lat,lon,type,active) VALUES (
			"'.$data['lid'].'",
			"'.$data['uid'].'",
			"'.$data['catid'].'",
			"'.$data['iconid'].'",
			"'.$data['parentid'].'",
			"'.$data['name'].'",
			"'.$data['address'].'",
			"'.$data['city'].'",
			"'.$data['state'].'",
			"'.$data['zip'].'",
			"'.$data['lat'].'",
			"'.$data['lon'].'",
			"'.$data['type'].'",
			"'.$data['active'].'"
		)';
		$results = mysql_query($query)or die(mysql_error());
	}

}