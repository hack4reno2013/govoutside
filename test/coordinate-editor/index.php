
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<link rel="stylesheet" href="styles/ui-lightness/jquery-ui-1.10.3.custom.min.css" />
		<script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.10.3.custom.min.js"></script>
		<script type="text/javascript" src="scripts/map.js"></script>
	</head>
	<body>

		<div style="width: 800px; height: 600px;">
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

	</body>
</html>