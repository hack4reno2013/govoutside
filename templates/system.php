<?php
$system_core = $this->registered_classes['System'];
?>
            <div id="sub-logo-area">
                <img src="assets/img/logo_lg.png" id="logo-sm" />
            </div>

<?php
	echo $system_core->renderAction($this->registered_classes);
?>