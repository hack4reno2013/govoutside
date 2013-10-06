<?php
<<<<<<< HEAD
$users_core = $registered_classes['Users'];
?>
            <div id="sub-logo-area">
                <img src="assets/img/logo_lg.png" id="logo-sm" />
            </div>
            <div id="lr-form-area">
                <h1><?php echo $page_title; ?></h1>
                <div>
                <?php
                	echo $users_core->renderAction();
                ?>
                </div>
            </div>
=======

$users_core = $registered_classes['Users'];
$action = 'Login';
	if(isset($_GET['action']))
	$action = $_GET['action'];
?>

<h1>Users - <?php echo ucwords($action); ?></h1>

<?php
	echo $users_core->renderAction();
?>
>>>>>>> 42ca065fb6de1d60a15672180a7fe00f29206cc1
