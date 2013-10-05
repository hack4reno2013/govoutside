var govOutsideWidget = {};
(function() {

	this.scriptUrl = '';
	this.target = document.getElementById('go-root');
	this.map = null;
	this.markers = [];
	this.mapData = null;
	this.widgetContainer = null;
	this.mapContainer = null;
	this.sidebarContainer = null;
	this.stylesLoaded = false;

	this.init = function() {
		if(this.target == null) {
			console.log('error: missing go-root element');
			return;
		}

		this.loadStylesheet();

		if(typeof google == 'undefined' || typeof google.maps == 'undefined') {
			this.loadGoogle();
		}
		else {
			this.getMapData();
		}
	};

	this.loadStylesheet = function() {
		var stylesheet;
		var d = document;
		var that = this;
		stylesheet = d.createElement('link');
		stylesheet.type = 'text/css';
		stylesheet.async = true;
		stylesheet.rel = 'stylesheet';
		stylesheet.href = 'assets/styles/widget.css';
		stylesheet.onload = function() {
			that.stylesLoaded = true;
		};
		document.getElementsByTagName('head')[0].appendChild(stylesheet);
	}

	this.loadGoogle = function() {
		var script;
		var d = document;
		script = d.createElement('script');
		script.type = 'text/javascript';
		script.async = true;
		script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false' + '&callback=govOutsideWidget.getMapData';
		d.getElementsByTagName('head')[0].appendChild(script);
	}

	this.getMapData = function() {
		var that = this;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var data = xmlhttp.responseText;
				var obj = JSON.parse(data);
				if(typeof obj == 'object') {
					that.mapData = obj;
					that.createMap();
				}
				else {
					console.log('error: invalid json response');
				}
			}
		}
		xmlhttp.open('GET', '../test/endpoint.php', true);
		xmlhttp.send();
	}

	this.getInitOptions = function() {
		if(typeof this.mapData.init !== 'undefined') {
			if(typeof this.mapData.init.center !== 'undefined') {
				var center = this.mapData.init.center;
				this.mapData.init.center = this.getLatLngObject(center.lat, center.lng);
			}
			return this.mapData.init;
		}
		else {
			return {};
		}
	}

	this.getLocations = function() {
		if(typeof this.mapData.locations !== 'undefined' && typeof this.mapData.locations == 'object' && this.mapData.locations.length > 0) {
			return this.mapData.locations;
		}
		else {
			return [];
		}
	}

	this.getLatLngObject = function(lat, lng) {
		return new google.maps.LatLng(lat, lng);
	}

	this.createMap = function() {
		var that = this;
		var stylesTimer = setInterval(function() {
			if(that.stylesLoaded) {
				clearInterval(stylesTimer);
				widgetContainer = that.appendElement('div', 'm-go-widget h-group', that.target);
				mapContainer = that.appendElement('div', 'm-go-map', widgetContainer);
				sidebarContainer = that.appendElement('div', 'm-go-sidebar', widgetContainer);
				var initOptions = that.getInitOptions();
				that.map = new google.maps.Map(mapContainer, initOptions);
				that.plotLocations();
			}
		}, 20);
		
	}

	this.plotLocations = function() {
		var locations = this.getLocations();
		var i = 0;
		var num_locations = locations.length;
		var bounds = new google.maps.LatLngBounds();
		while(i < num_locations) {
			var location = locations[i];
			var latlng = this.getLatLngObject(location.lat, location.lng);
			bounds.extend(latlng);
			var marker = new google.maps.Marker({
				position: latlng,
				map: this.map,
				title: location.title
			});
			this.markers.push(marker);
			i++;
		}
		this.map.fitBounds(bounds);
		this.map.panToBounds(bounds);
	}

	this.appendElement = function(elementType, elementClass, elementParent) {
		if(typeof elementType == 'undefined') {
			elementType = 'div';
		}

		var element = document.createElement(elementType);
		
		if(typeof elementClass !== 'undefined') {
			element.className = elementClass;
		}
		if(typeof elementParent !== 'undefined') {
			elementParent.appendChild(element);
		}
		console.log(element);
		return element;

	}

}).apply(govOutsideWidget);