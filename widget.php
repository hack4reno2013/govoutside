
<!DOCTYPE html>
<html lang="en-us">
	<head>

		<script type="text/javascript">

		(function(d, script) {
			var go_api_key = '1234';
			script = d.createElement('script');
			script.type = 'text/javascript';
			script.async = true;
			script.onload = function(){
				govOutsideWidget.init(go_api_key);
			};
			script.src = 'assets/scripts/widget.js';
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