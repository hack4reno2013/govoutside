<?php

$users_core = $this->registered_classes['Users'];
$action = 'Login';
	if(isset($_GET['action']))
	$action = $_GET['action'];
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
