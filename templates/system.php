<?php
$system_core = $this->registered_classes['System'];

?>
<h1>System</h1>

<?php
	echo $system_core->renderAction($this->registered_classes);
?>