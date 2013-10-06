<?php

$users_core = $this->registered_classes['Users'];
$action = 'Login';
	if(isset($_GET['action']))
	$action = $_GET['action'];
?>

<h1>Users - <?php echo ucwords($action); ?></h1>

<?php
	echo $users_core->renderAction();
?>