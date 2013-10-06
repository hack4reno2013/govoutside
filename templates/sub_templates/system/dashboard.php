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
        if($categories == false){
        ?>
        <div>To start please create at least one category</div>
        <?php
        }
        foreach($categories as $category){
        ?>
        <div class="category" style="background-color:<?=$category['color']?>;" data-color='<?=$category['color']?>' data-label='<?=$category['label']?>' data-id="<?=$category['catid']?>"><?=$category['label']?></div>
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
    <table>
    <?php
    $locations = $this->getLocations();
    print_r($locations);
    foreach($locations as $location){
    ?>
    <tr>
        <td style="background-color: <?=$location['color']?>; color: #fff;"><?=$location['label']?></td>
        <td><?=$location['name']?></td>
        <td><?=$location['address']?></td>
    </tr>
    <?php
    }
    ?>
    </table>
    <?php
        if($categories){
    ?>
    <h3>Add Location</h3>
    <div id="locations-edit-area" class="rwmb-map-field">
        <div id="location-map-area">
            <div style="width: 450px; height: 300px;">
                    <div class="rwmb-map-canvas" style="width: 100%; height: 100%;">
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
    <?php
        }
    ?>
</div>
		<script type="text/javascript" src="assets/scripts/main.js"></script>
