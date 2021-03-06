@extends('layouts.master')

@section('title')
CyclingCols - Search On Map
@stop

@section('content')
<div id="canvas">				
	<div class="canvas col-xs-12 col-sm-12 col-md-12 col-lg-12" id="map-canvas"></div>	
</div>

<?php
	$latitude = 51;
	$longitude = 9.7;
	$zoom_level = 4;
	
	$colID = 0;
	
	if (isset($country))
	{
		$latitude = $country->Latitude/1000000;
		$longitude = $country->Longitude/1000000;
		$zoom_level = 6;
	}
	elseif (isset($region))
	{
		$latitude = $region->Latitude/1000000;
		$longitude = $region->Longitude/1000000;
		$zoom_level = 7;
	}
	
	if (isset($subregion))
	{
		$latitude = $subregion->Latitude/1000000;
		$longitude = $subregion->Longitude/1000000;
		$zoom_level = 8;
	}
	
	if (isset($col))
	{
		$latitude = $col->Latitude/1000000;
		$longitude = $col->Longitude/1000000;
		$zoom_level = 10;
		$colID = $col->ColID;
	}
?>	

<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@2.2.3/dist/esri-leaflet.js"
    integrity="sha512-YZ6b5bXRVwipfqul5krehD9qlbJzc6KOGXYsDjU9HHXW2gK57xmWl2gU6nAegiErAqFXhygKIsWPKbjLPXVb2g=="
    crossorigin=""></script>
  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.css"
    integrity="sha512-v5YmWLm8KqAAmg5808pETiccEohtt8rPVMGQ1jA6jqkWVydV5Cuz3nJ9fQ7ittSxvuqsvI9RSGfVoKPaAJZ/AQ=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@2.2.13/dist/esri-leaflet-geocoder.js"
    integrity="sha512-zdT4Pc2tIrc6uoYly2Wp8jh6EPEWaveqqD3sT0lf5yei19BC1WulGuh5CesB0ldBKZieKGD7Qyf/G0jdSe016A=="
    crossorigin=""></script>

<script type="text/javascript">
	var map;
	var markers = [];
	var mapReady = false;
	var colsReady = false;
	var countriesReady = false;
	var regionsReady = false;
	var subregionsReady = false;
	var markersShown = false;
	
	var zoom = {{$zoom_level}};
	var lat = {{$latitude}};
	var lng = {{$longitude}};
	
	if (history.state){
		zoom = history.state.zoom;
		lat = history.state.latitude;
		lng = history.state.longitude;
	}
	
	var colID = {{$colID}};
	
	var tryShowMarkers = function(count){
		if (!count) count = 1;
		if (count > 200) return; //takes too long...
	
		if (mapReady && colsReady && countriesReady && regionsReady && subregionsReady){
			showMarkers();
		} else {
			setTimeout(function(){
				tryShowMarkers(count+1);
			},100);
		}
			
		console.log("tryShowMarkers ready");
	}
	
	window.onload = function() {
		var mapOptions = {
			attributionControl: false
		};
		map = L.map('map-canvas', mapOptions).on('load',function(){ mapReady = true; }).setView([lat, lng], zoom);
		
		map.on('zoomend', function() {
			showMarkers();
		});
		
		map.on('move', function() {
			showMarkers();
		});	
		
		map.on('dragend', function() {
			showMarkers();
		});		
		
		map.on('focus', function() {
			showMarkers();
			console.log("focus");
		});
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		/*L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			minZoom: 4,
			//maxZoom: 4,
			id: 'mapbox.streets',
			accessToken: 'pk.eyJ1IjoiY3ljbGluZ2NvbHMiLCJhIjoiY2pudTdycTc4MDc2ZTNyb2kyMTUzampjcCJ9.PUlrY1MZeyqtE8_WKj7Smw'
		}).addTo(map);*/
		
		var searchControl = L.esri.Geocoding.geosearch().addTo(map);

		var results = L.layerGroup().addTo(map);

		searchControl.on('results', function(data){
			results.clearLayers();
			for (var i = data.results.length - 1; i >= 0; i--) {
				results.addLayer(L.marker(data.results[i].latlng));
			}
		});
	}
	
	var showMarkers = function(){
		var showCount = 0;
		var hideCount = 0;
	
		var zoom = map.getZoom();
		var bounds = map.getBounds();
		var padding = 0.05;
		
		var latMin = bounds._southWest.lat;
		var latMax = bounds._northEast.lat;
		var latDiff = latMax - latMin;
		latMin -= latDiff*padding;
		latMax += latDiff*padding;
		
		var lngMin = bounds._southWest.lng;
		var lngMax = bounds._northEast.lng;
		var lngDiff = lngMax - lngMin;
		lngMin -= lngDiff*padding;
		lngMax += lngDiff*padding;
		
		for(var i = 0; i < markers.length; i++){
			var marker = markers[i];
			if (zoom >= marker.minZoom && zoom <= marker.maxZoom && 
				marker.lat > latMin && marker.lat < latMax && 
				marker.lng > lngMin && marker.lng < lngMax)
			{
				if (!marker.shown){
					markers[i].marker.addTo(map);
					marker.shown = true;
					showCount++;
				}
			} else {
				if (marker.shown){
					markers[i].marker.remove();
					marker.shown = false;
					hideCount++;
				}
			}
		}
		
		//console.log("#markers: " + markers.length);
		//console.log("latMin: " + latMin + ", latMax: " + latMax + ", lngMin: " + lngMin + ", lngMax: " + lngMax + ", zoom: " + zoom);
		//console.log(showCount + " markers shown, " + hideCount + " markers hidden");
	}
	
	var getCols = function() {
		var cols = new Array();
		var names = new Array();
		var ids = new Array();
		var heights = new Array();
		var colMarker = null;
		
		//colsMarkers = new L.FeatureGroup();

		$.ajax({
			url : "{{ URL::asset('ajax/getcols.php') }}",
			data : "",
			dataType : 'json',
			success : function(data) {
			
				for(var j = 0; j < data.length; j++)
				{								
					var img;
					if (data[j].Height > 2500) { img = "/images/ColRed.png"; }
					else if (data[j].Height > 1500) { img = "/images/ColDarkOrange.png"; }
					else if (data[j].Height > 1000) { img = "/images/ColOrange.png"; }
					else if (data[j].Height > 500) { img = "/images/ColYellow.png"; }
					else { img = "/images/ColLightYellow.png"; }
									
					var title = data[j].Col + ' (' + data[j].Height + 'm)';	
					
					var icon = L.icon({
						iconUrl: img,
						//iconSize: [38, 95],
						iconAnchor: [16,35]
						//popupAnchor: [-3, -76],
						//shadowUrl: 'my-icon-shadow.png',
						//shadowSize: [68, 95],
						//shadowAnchor: [22, 94]
					});
						
					var markerOptions = {
						icon: icon,
						title: title
					};
					var lat_ = data[j].Latitude/1000000;
					var lng_ = data[j].Longitude/1000000;
					var marker = L.marker([lat_, lng_], markerOptions);
					
					markers.push({
						marker: marker,
						lat: lat_,
						lng: lng_,
						minZoom: 9,
						maxZoom: 1000,
						shown: false
					});
					
					names[j] = data[j].Col;
					ids[j] = data[j].ColID;
					heights[j] = data[j].Height						
					cols[j] = marker;
					
					// Adding a click event to the marker
					(function(j){
						var url = "/col/" + data[j].ColIDString;
					
						addClickCols(cols[j], title, url);	
						
						if (parseInt(data[j].ColID) == colID){
							colMarker = {
								marker: marker,
								title: title,
								url: url
							};
						}
					})(j);					
				}
				
				colsReady = true;
				//console.log("cols ready");
			}
		})
	};	
			
	var getCountries = function() {	
		var geos = new Array();
		var lat = new Array();
		var lng = new Array();
		var minzooms = new Array();
		var maxzooms = new Array();
		var nrcols = new Array();
		var i = 0;
		
		//load countries
		$.ajax({
			url : "{{ URL::asset('ajax/getcountries.php') }}",
			data : "",
			dataType : 'json',
			success : function(data) {
				for(var j = 0; j < data.length; j++)
				{								
					var title = data[j].Country + ' (' + data[j].NrCols + ' cols)';	
					
					lat[i] = data[j].Latitude/1000000;
					lng[i] = data[j].Longitude/1000000;
					minzooms[i] = parseInt(1);
					
					switch (parseInt(data[j].CountryID))
					{
						case 4: 
						case 5: 
						case 7: 
						case 4417: //FRA,ITA,SPA,GER
							maxzooms[i] = parseInt(5); break;
						case 3: 
						case 8: //AUT,SWI
							maxzooms[i] = parseInt(6); break;
						default:
							maxzooms[i] = parseInt(8); break;
					}
					
					var icon = L.icon({
						iconUrl: "/images/ColRed.png",
						//iconSize: [38, 95],
						iconAnchor: [16,35]
						//popupAnchor: [-3, -76],
						//shadowUrl: 'my-icon-shadow.png',
						//shadowSize: [68, 95],
						//shadowAnchor: [22, 94]
					});
						
					var markerOptions = {
						icon: icon,
						title: title
					};
					var lat_ = data[j].Latitude/1000000;
					var lng_ = data[j].Longitude/1000000;
					var marker = L.marker([lat_, lng_], markerOptions);
					
					markers.push({
						marker: marker,
						lat: lat_,
						lng: lng_,
						minZoom: minzooms[i],
						maxZoom: maxzooms[i]
					});
								
					geos[i] = marker;
					
					// Adding a click event to the marker
					(function(i){
						var newZoom = maxzooms[i];
						newZoom = parseInt(newZoom) + 1;
						var lat_ = lat[i];
						var lng_ = lng[i];
						
						if (data[j].CountryID == 5834) {//Greece
							lat_ = 35.27;
							lng_ = 24.85;
							//zoom at Crete
						}
						
						addClickZoom(geos[i], title, newZoom, lat_, lng_);	
					})(i);

					i = i + 1;
				}			
				
				countriesReady = true;
				//console.log("countries ready");
			}
		})
	}	
	
				
	var getRegions = function() {	
		var geos = new Array();
		var lat = new Array();
		var lng = new Array();
		var minzooms = new Array();
		var maxzooms = new Array();
		var nrcols = new Array();
		var i = 0;
		
		//load regions
		$.ajax({
			url : "{{ URL::asset('ajax/getregions.php') }}",
			data : "",
			dataType : 'json',
			success : function(data) {
				for(var j = 0; j < data.length; j++)
				{								
					var title = data[j].Region + ' (' + data[j].NrCols + ' cols)';	
					
					lat[i] = data[j].Latitude/1000000;
					lng[i] = data[j].Longitude/1000000;
					
					switch (parseInt(data[j].CountryID))
					{
						case 4: 
						case 5: 
						case 7: 
						case 4417: //FRA,ITA,SPA,GER
							minzooms[i] = parseInt(6); break;
						case 3: 
						case 8: //AUT,SWI
							minzooms[i] = parseInt(7); break;
						default:
							minzooms[i] = parseInt(8); break;
					}
					
					if (data[j].NrSubRegions > 0)
					{
						maxzooms[i] = minzooms[i];
					}
					else
					{
						maxzooms[i] = parseInt(8);
					}
					
					var icon = L.icon({
						iconUrl: "/images/ColDarkOrange.png",
						//iconSize: [38, 95],
						iconAnchor: [16,35]
						//popupAnchor: [-3, -76],
						//shadowUrl: 'my-icon-shadow.png',
						//shadowSize: [68, 95],
						//shadowAnchor: [22, 94]
					});
						
					var markerOptions = {
						icon: icon,
						title: title
					};
					var lat_ = data[j].Latitude/1000000;
					var lng_ = data[j].Longitude/1000000;
					var marker = L.marker([lat_, lng_], markerOptions);
					
					markers.push({
						marker: marker,
						lat: lat_,
						lng: lng_,
						minZoom: minzooms[i],
						maxZoom: maxzooms[i]
					});
								
					geos[i] = marker;
					
					// Adding a click event to the marker
					(function(i){
						var newZoom = maxzooms[i];
						newZoom = parseInt(newZoom) + 1;
						var lat_ = lat[i];
						var lng_ = lng[i];
						
						if (data[j].RegionID == 10494) {//Bayern
							lat_ = 47.91;
							lng_ = 11.84;//zoom at south
						}
						
						addClickZoom(geos[i], title, newZoom, lat_, lng_);	
					})(i);

					i = i + 1;
				}			
		
				regionsReady = true;
				//console.log("regions ready");
			}
		})
	}		
				
	var getSubRegions = function() {	
		var geos = new Array();
		var lat = new Array();
		var lng = new Array();
		var minzooms = new Array();
		var maxzooms = new Array();
		var nrcols = new Array();
		var i = 0;
		
		//load subregions
		$.ajax({
			url : "{{ URL::asset('ajax/getsubregions.php') }}",
			data : "",
			dataType : 'json',
			success : function(data) {
				for(var j = 0; j < data.length; j++)
				{								
					var title = data[j].SubRegion + ' (' + data[j].NrCols + ' cols)';	
					
					lat[i] = data[j].Latitude/1000000;
					lng[i] = data[j].Longitude/1000000;
					
					switch (parseInt(data[j].CountryID))
					{
						case 4: 
						case 5: 
						case 7: 
						case 4417: //FRA,ITA,SPA,GER
							minzooms[i] = parseInt(7); break;
						default:
							minzooms[i] = parseInt(8); break;
					}
					maxzooms[i] = parseInt(8);
					
					var icon = L.icon({
						iconUrl: "/images/ColLightYellow.png",
						//iconSize: [38, 95],
						iconAnchor: [16,35]
						//popupAnchor: [-3, -76],
						//shadowUrl: 'my-icon-shadow.png',
						//shadowSize: [68, 95],
						//shadowAnchor: [22, 94]
					});
						
					var markerOptions = {
						icon: icon,
						title: title
					};
					var lat_ = data[j].Latitude/1000000;
					var lng_ = data[j].Longitude/1000000;
					var marker = L.marker([lat_, lng_], markerOptions);
					
					markers.push({
						marker: marker,
						lat: lat_,
						lng: lng_,
						minZoom: minzooms[i],
						maxZoom: maxzooms[i]
					});
								
					geos[i] = marker;
					
					// Adding a click event to the marker
					(function(i){
						var newZoom = maxzooms[i];
						newZoom = parseInt(newZoom) + 1;
						addClickZoom(geos[i], title, newZoom, lat[i], lng[i]);	
					})(i);

					i = i + 1;
				}	
		
				subregionsReady = true;
				//console.log("subregions ready");		
			}
		})
	}	
	
	getCols();
	getCountries();
	getRegions();
	getSubRegions();
	
	tryShowMarkers();
	
	addClickZoom = function(marker, title, zoom, lat, lng) {
		marker.on('click', function() {
			map.setView([lat, lng], zoom);
		});
	}
	
	addClickCols = function(marker, title, url) {
		marker.on('click',function(){
			if (history.pushState){
				var stateObj = { 
					latitude: map.getCenter().lat,
					longitude: map.getCenter().lng,
					zoom: map.getZoom()
				};
				history.pushState(stateObj, null, window.location.href);
			}
			parent.document.location.href = url;
		});
	}
	
</script>

@stop
