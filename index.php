<?php

require_once('functions.php');

$core = new govOutSide();

//template generation
$templateArray = $core->getTemplates();
$currentTemplate = $templateArray[0];
if(isset($_GET['view'])){
$requstedTemplate = $_GET['view'];
	if(!empty($requstedTemplate)){
		$checkTemplate = $core->checkTemplate($requstedTemplate);
		if($checkTemplate==(-1)) {
			$checkTemplate = 999;	
		}		
		$currentTemplate = $templateArray[$checkTemplate];
	}
}


$template = $core->renderTemplate($currentTemplate);