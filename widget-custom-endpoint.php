<!DOCTYPE html>
<html lang="en-us">
	<head>

		<script type="text/javascript">

		(function(d, script) {
			var api_key = 'am9zaC5rb2JlcnN0ZWluQGdtYWlsLmNvbTpKb3NoX19Lb2JlcnN0ZWluOjM4OTg=';
			var endpoint = '//' + location.host + '/?view=ajax&api_key=' + api_key
			script = d.createElement('script');
			script.type = 'text/javascript';
			script.async = true;
			script.onload = function(){
				govOutsideWidget.init(api_key, endpoint);
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