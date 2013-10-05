<?php
$users_core = $registered_classes['Users'];

?>

<h1>Users - <?php echo $page_title; ?></h1>

<?php
	echo $users_core->renderAction();
?>