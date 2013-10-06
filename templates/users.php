<?php
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
