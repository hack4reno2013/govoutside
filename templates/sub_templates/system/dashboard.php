<?php

?>
<h2>Cateogries</h2>
<div id="categories-area">
<?php
$categories = $this->getCategories();
foreach($categories as $category){
?>
<div class="category" style="background-color:<?=$category['color']?>;"><?=$category['label']?></div>
<?php
}
?>
</div>
<h2>Add Category</h2>
<div id="categories-form-area">
<?php
$categories_form_fields = $this->getFormFields('categories');
echo $this->formMediator($categories_form_fields, 'categories');
?>
</div>
<h2>Locations</h2>
<h2>Add Location</h2>
<div id="locations-form-area">
<?php
$location_form_fields = $this->getFormFields('location');
echo $this->formMediator($location_form_fields, 'location');
?>
</div>