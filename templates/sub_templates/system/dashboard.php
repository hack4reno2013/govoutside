		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<link rel="stylesheet" href="assets/styles/vendor/jquery-ui/jquery-ui-1.10.3.custom.min.css" />
		<link rel="stylesheet" href="assets/css/colorpicker.css" />
		<script type="text/javascript" src="assets/scripts/vendor/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="assets/scripts/vendor/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="assets/scripts/map.js"></script>
        <link rel="stylesheet" media="screen" type="text/css" href="assets/css/colorpicker.css" />
		<script type="text/javascript" src="assets/scripts/vendor/colorpicker.js"></script>
<?php

?>
<div id="edit-wrapper">
    <h2>Cateogries</h2>
    <div id="categories-area">
        <?php
        $categories = $this->getCategories();
        foreach($categories as $category){
        ?>
        <div class="category" style="background-color:<?=$category['color']?>;" data-id="<?=$category['catid']?>"><?=$category['label']?></div>
        <?php
        }
        ?>
    </div>
    <h3>Add Category</h3>
    <div id="categories-form-area">
        <?php
        $categories_form_fields = $this->getFormFields('categories');
        echo $this->formMediator($categories_form_fields, 'categories');
        ?>
    </div>
    <h2>Locations</h2>
    <h3>Add Location</h3>
    <div id="locations-edit-area">
        <div id="location-map-area">
            <div style="width: 450px; height: 300px;">
                <div class="rwmb-map-field" style="width: 100%; height: 100%;">
                    <div class="rwmb-map-canvas" style="width: 100%; height: 100%;">
                    </div>
                    <input type="text" name="latitude" class="rwmb-map-latitude" value="" />
                    <br />
                    <input type="text" name="longitude" class="rwmb-map-longitude" value="" />
                    <br />
                    <input type="text" name="address" id="address-input" />
                    <button class="button rwmb-map-goto-address-button" type="button" value="address-input">Find Address</button>
                </div>
            </div>
        </div>
        <div id="location-form-area">
        <?php
        $location_form_fields = $this->getFormFields('location');
        echo $this->formMediator($location_form_fields, 'location');
        ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
		<script type="text/javascript" src="assets/scripts/main.js"></script>
