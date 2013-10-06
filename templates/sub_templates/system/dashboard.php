<?php

?>

Dashboard

<?php

$categories_form_fields = $this->getFormFields('categories');
echo $this->formMediator($categories_form_fields);

$location_form_fields = $this->getFormFields('location');
echo $this->formMediator($location_form_fields);

?>