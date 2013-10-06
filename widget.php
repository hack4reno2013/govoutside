
<!DOCTYPE html>
<html lang="en-us">
	<head>

		<script type="text/javascript">

		(function(d, script) {
			<?php
			$api_key = (isset($_GET['api_key'])) ? $_GET['api_key'] : null;
			?>
			var go_api_key = '<?php echo $api_key; ?>';
			script = d.createElement('script');
			script.type = 'text/javascript';
			script.async = true;
			script.onload = function(){
				govOutsideWidget.init(go_api_key);
			};
			script.src = 'http://govoutside.com/assets/scripts/widget.min.js';
			d.getElementsByTagName('head')[0].appendChild(script);
		}(document));

		</script>

	</head>
	<body>

		<div style="width: 800px; height: 600px;">
			<div id="go-root">

			</div>
		</div>

	</body>
</html>