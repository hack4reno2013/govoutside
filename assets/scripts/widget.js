var govOutsideWidget = {};
(function() {

	thisApp = this;

	this.host = location.host;
	this.target = document.getElementById('go-root');
	this.map = null;
	this.markers = [];
	this.mapData = null;
	this.locations = null;
	this.categories = null;

	this.widgetContainer = null;
	this.mapContainer = null;
	this.sidebarContainer = null;
	this.locationsList = null;
	this.topbarContainer = null;
	this.categoriesList = null;

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
		xmlhttp.open('GET', '//' + this.host + '/test/endpoint.php', true);
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
		if(typeof this.mapData.locations == 'object' && this.mapData.locations.length > 0) {
			return this.mapData.locations;
		}
		else {
			return [];
		}
	}

	this.getCategories = function() {
		if(typeof this.mapData.categories == 'object' && this.mapData.categories.length > 0) {
			var defaultCategories = [
				{
					'slug': 'all',
					'title': 'All'
				}
			];

			for(var i = 0; i < this.mapData.categories.length; i++) {
				defaultCategories.push(this.mapData.categories[i]);
			}

			return defaultCategories;
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

				that.widgetContainer = that.appendElement('div', 'm-go-widget h-group', that.target);
				that.topbarContainer = that.appendElement('div', 'm-go-topbar', that.widgetContainer);
				that.categoriesList = that.appendElement('ul', '', that.topbarContainer);
				that.mapContainer = that.appendElement('div', 'm-go-map', that.widgetContainer);
				that.sidebarContainer = that.appendElement('div', 'm-go-sidebar', that.widgetContainer);
				that.locationsList = that.appendElement('ul', '', that.sidebarContainer);

				var initOptions = that.getInitOptions();
				that.map = new google.maps.Map(that.mapContainer, initOptions);
				that.locations = that.getLocations();
				that.categories = that.getCategories();

				that.plotLocations();
				that.populateTopbar();
				that.populateSidebar();
			}
		}, 20);
		
	}

	this.plotLocations = function() {
		var i = 0;
		var locations = this.locations;
		var num_locations = locations.length;
		var bounds = new google.maps.LatLngBounds();
		var that = this;
		while(i < num_locations) {
			var location = locations[i];
			var latlng = this.getLatLngObject(location.lat, location.lng);
			bounds.extend(latlng);
			var marker = new google.maps.Marker({
				position: latlng,
				map: this.map,
				title: location.title,
				icon: this.getIcon('//' + this.host + '/assets/images/icons/green.png', 47, 61)

			});
			marker.location_index = i;
			marker.category = location.category;
			this.markers.push(marker);

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					that.onLocationClick(i);
				}
			})(marker, i));

			i++;
		}
		this.map.fitBounds(bounds);
		this.map.panToBounds(bounds);
	}

	this.getIcon = function(fileUrl, width, height) {
		return new google.maps.MarkerImage(
			fileUrl,
			null,
			null,
			null,
			new google.maps.Size(width, height)
		);
	}

	this.populateTopbar = function() {
		var that = this;
		for(var i = 0; i < this.categories.length; i++) {
			var category = this.categories[i];
			var attributes = {
				'data-category': category.slug
			};
			var elementClass = '';
			if(category.slug == 'all') {
				elementClass = 'active';
			}
			var element = this.appendElement('li', elementClass, this.categoriesList, category.title, attributes);
			element.onclick = function(event) {
				that.onCategoryClick(this.getAttribute('data-category'));
			};
		}
	}

	this.populateSidebar = function() {
		var that = this;
		var locations = this.locations;
		for(var i = 0; i < locations.length; i++) {
			var location = this.locations[i];
			var attributes = {
				'data-location-index': i,
				'data-category': location.category
			};
			var element = this.appendElement('li', '', this.locationsList, locations[i].title, attributes);
			element.onclick = function (event) {
				that.onLocationClick(this.getAttribute('data-location-index'));
			};
		}
	}

	this.appendElement = function(elementType, elementClass, elementParent, innerHtml, attributes) {
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
		if(typeof innerHtml !== 'undefined') {
			element.innerHTML = innerHtml;
		}
		if(typeof attributes == 'object') {
			for(var key in attributes) {
				element.setAttribute(key, attributes[key]);
			}
		}
		return element;

	}

	this.onLocationClick = function(location_index) {
		this.setSidebarLocationActive(location_index);
		this.setMapLocationActive(location_index);
	}

	this.setSidebarLocationActive = function(location_index) {
		location_index = String(location_index);
		for(var i = 0; i < this.locationsList.childNodes.length; i++) {
			var element = this.locationsList.childNodes[i];
			var element_location_index = element.getAttribute('data-location-index');
			if(element_location_index === location_index) {
				this.addClass(element, 'active');
			}
			else {
				this.removeClass(element, 'active');
			}
		}
	}

	this.setMapLocationActive = function(location_index) {
		for(var i = 0; i < this.markers.length; i++) {
			var marker = this.markers[i];
			if(marker.location_index == location_index) {
				this.map.panTo(marker.position);
			}
		}
	}

	this.onCategoryClick = function(category) {
		this.setTopbarLocationActive(category);
		this.filterSidebarByCategory(category);
		this.filterMapByCategory(category);
	}

	this.setTopbarLocationActive = function(category) {
		for(var i = 0; i < this.categoriesList.childNodes.length; i++) {
			var element = this.categoriesList.childNodes[i];
			var element_category = element.getAttribute('data-category');
			if(element_category === category) {
				this.addClass(element, 'active');
			}
			else {
				this.removeClass(element, 'active');
			}
		}
	}

	this.filterSidebarByCategory = function(category) {
		for(var i = 0; i < this.locationsList.childNodes.length; i++) {
			var element = this.locationsList.childNodes[i];
			var element_category = element.getAttribute('data-category');

			if(element_category == category || category == 'all') {
				this.removeClass(element, 'hidden');
			}
			else {
				this.addClass(element, 'hidden');
			}
		}
	}

	this.filterMapByCategory = function(category) {
		var bounds = new google.maps.LatLngBounds();
		for(var i = 0; i < this.markers.length; i++) {
			var marker = this.markers[i];
			if(marker.category == category || category == 'all') {
				bounds.extend(marker.position);
				marker.setMap(this.map);
			}
			else {
				marker.setMap(null);
			}
		}
		this.map.fitBounds(bounds);
		this.map.panToBounds(bounds);
	}

	this.hasClass = function(element, className) {
		return new RegExp(' ' + className + ' ').test(' ' + element.className + ' ');
	}

	this.addClass = function(element, className) {
		element.className += ' ' + className;
	}

	this.removeClass = function(element, className) {
		var newClass = ' ' + element.className.replace( /[\t\r\n]/g, ' ') + ' ';
		if (this.hasClass(element, className)) {
			while (newClass.indexOf(' ' + className + ' ') >= 0 ) {
				newClass = newClass.replace(' ' + className + ' ', ' ');
			}
			element.className = newClass.replace(/^\s+|\s+$/g, '');
		}
	}

}).apply(govOutsideWidget);