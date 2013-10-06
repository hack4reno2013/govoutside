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
    <h2>Step 1: Add Cateogries</h2>
    <div id="categories-area">
        <?php
        $categories = $this->getCategories();
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
    <?php
        if($categories){
    ?>
    <h2>Step 2: Add Locations</h2>
    <table id="locations-table">
    <?php
    $locations = $this->getLocations();
    foreach($locations as $location){
    ?>
    <tr align="center">
        <td width="30%" style="background-color: <?=$location['color']?>; color: #fff;"><?=$location['label']?></td>
        <td width="30%" ><?=$location['name']?></td>
        <td width="30%" ><?=$location['address']?></td>
    </tr>
    <?php
	}
    ?>
    </table>
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
    <?php
        if($locations){
    ?>
    <h2>Step 3: Get the Code</h2>
    <textarea style=" width: 450px; height: 300px;">
<div id="go-root"></div>
<script type="text/javascript">
(function(d, script) {
    var api_key = '<?=$this->registered_classes['Users']->user['api_key']?>';
    script = d.createElement('script');
    script.type = 'text/javascript';
    script.async = true;
    script.onload = function(){
        govOutsideWidget.init(api_key);
    };
    script.src = 'http://govoutside.com/assets/scripts/widget.js';
    d.getElementsByTagName('head')[0].appendChild(script);
}(document));
</script>
    </textarea>
</div>
    <?php
        }
    ?>
		<script type="text/javascript" src="assets/scripts/main.js"></script>
