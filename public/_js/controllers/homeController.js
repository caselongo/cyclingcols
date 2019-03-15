(function () {
	'use strict';

	angular.module('homeSweatHome')
		.controller('homeController', homeController);
		
	homeController.$inject = ['$location','$window','strava_access_token','homeService','usSpinnerService'];

	function homeController($location,$window,strava_access_token,homeService,usSpinnerService) {
		var vm = this;
		
		vm.strava_access_token = strava_access_token;
		
		if (!strava_access_token.value){
			$location.path("/auth");
		}
		
		/*spinner*/
		var startSpin = function(name){
			usSpinnerService.spin(name);
		}
		
		var stopSpin = function(name){
			usSpinnerService.stop(name);
		}		
		/*~spinner*/
		
		var map;
		var encodedPath;
		var decodedPath;
		var path;
		var home;
		var furthest;
		var polylines = [];

		var infowindow;

		var POLYLINE_STROKEWEIGHT = 2;
		var POLYLINE_STROKEWEIGHT_SELECTED = 4;
		
		var allActivities = [];
		var activities = [];
		var dtActivities;
		vm.years = [];
		vm.selectedActivity = null;
		
		var chart;
		
		var page = 0;
		var earliestYear = 9999;
		
		vm.getPrevYear = function(){
			var prevYear = vm.years[vm.years.length-1].year - 1;
			getActivities(prevYear);
		}
		
		var getActivities = function(_year){
			startSpin('spinner-calendar');
		
			page = page + 1;
			var url = "https://www.strava.com/api/v3/athlete/activities?access_token=" + strava_access_token.value + "&page=" + page;
			
			homeService.getActivities(url)	
				.then(function(data){
					
					for(var i = 0; i < data.length; i++){
						var start_date = data[i].start_date.split("T");
						if (start_date.length == 2){
							start_date = start_date[0].split("-");
							if (start_date.length == 3){
								data[i].year = Number(start_date[0]);
								data[i].month = Number(start_date[1]) - 1;
								data[i].day = Number(start_date[2]);
								data[i].dist = Math.round(data[i].distance/1000);
								data[i].monthname = months[data[i].month];
								
								allActivities.push(data[i]);
								//dtActivities.addRow([ new Date(year, month, day), dist, tooltip ]);
								
								if (data[i].year < earliestYear){
									earliestYear = data[i].year;
								}
															
								if (!_year){
									_year = data[i].year;
								}					
							}
						}			
					}
					
					if (earliestYear >= _year){
						getActivities(_year);
					} else {
						vm.drawCalendar(_year);
						
						for (var j = 0; j < vm.years.length; j++){
							vm.years[j].selected = false;
						}
						
						vm.years.push({year: _year, selected: true});
						
						stopSpin('spinner-calendar');
					}
					
				},
				function(errorData){
				}			
			);			
		}
		
		vm.drawCalendar = function(_year){
			if (chart){
				chart.clearChart();	
			}
			chart = new google.visualization.Calendar(document.getElementById('calendar'));
			
			function selectHandler() {
				var selectedItem = chart.getSelection()[0];
				if (selectedItem) {
					vm.selectedActivity = activities[selectedItem.row];
					//var activityid = activities[selectedItem.row].id;
					showActivity(vm.selectedActivity.id);
				}
			}

			google.visualization.events.addListener(chart, 'select', selectHandler);

			var options = {
				calendar: {cellSize: 20},
				colorAxis: {minValue: 0, maxValue: 200, colors: ['#FFFFFF', '#fc4c02']},
				legend: 'none',
				title: "Select an activity",
				tooltip: {isHtml: true}
			};
			
			dtActivities = new google.visualization.DataTable();		   
			dtActivities.addColumn({ type: 'date', id: 'Date' });		   
			dtActivities.addColumn({ type: 'number', id: 'Distance' });
			dtActivities.addColumn({ type: 'string', role: 'tooltip', 'p': {'html': true}}); 
			
			activities = [];

			for(var i = 0; i < allActivities.length; i++){
				if (allActivities[i].year == _year){					
					var tooltip = "<div class=\"tooltip-header\"><b>" + allActivities[i].day + " " + allActivities[i].monthname + " " + allActivities[i].year + "</b> " + allActivities[i].dist + " km</div>";
					tooltip += "<div class=\"tooltip-name\">" + allActivities[i].name + "</div>";
					
					activities.push(allActivities[i]);
					
					dtActivities.addRow([ new Date(allActivities[i].year, allActivities[i].month, allActivities[i].day), allActivities[i].dist, tooltip ]);
				}
			}

			chart.draw(dtActivities, options);
		}
					
		var showActivity = function(activityid){
			startSpin('spinner-map');
			
			var url = "https://www.strava.com/api/v3/activities/" + activityid + "?access_token=" + strava_access_token.value;
			
			homeService.getActivity(url)
				.then(function(data){
					if (data.map.polyline) {
						encodedPath = data.map.polyline;
					} else {	
						encodedPath = data.map.summary_polyline;
					}
					//lat = data.start
					decodePath();
					drawMap();
					calcDistanceFromHome(path,map);
					//drawTrack();
					drawMovingCloserOrFurther();
					
					stopSpin('spinner-map');	
				},
				function(errorData){
				}			
			);	
		}
					
		var drawMap = function(){
			var myLatlng = new google.maps.LatLng(51.65905179951626, 7.3835928124999555);
			var myOptions = {
				zoom: 8,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map = new google.maps.Map(document.getElementById("map"), myOptions);
				
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < decodedPath.length; i++) {
				bounds.extend(decodedPath[i]);
			}
			
			map.fitBounds(bounds);
		}

		var decodePath = function(){
			decodedPath = google.maps.geometry.encoding.decodePath(encodedPath); 
			/*var decodedLevels = decodeLevels("BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");*/
			
			path = decodePolyline(encodedPath,5);
		}


		var drawTrack = function(){
			polylines = [];
			drawPolyline(path,"#FF0000");
		}

		var drawPolyline = function(_path,_strokeColor, title){
			var polylinePath = createPolylinePath(_path);

			if(!_strokeColor){
				_strokeColor = "#FF0000";
			}
			
			var polyline = new google.maps.Polyline({
				path: polylinePath,
				/*levels: decodedLevels,*/
				strokeColor: _strokeColor,
				strokeOpacity: 1.0,
				strokeWeight: POLYLINE_STROKEWEIGHT,
				map: map		
			});
			
			polylines.push(polyline);
			
			// Open the InfoWindow on mouseover:
			if (title){
				google.maps.event.addListener(polyline, 'click', function(e) {
					if(!infowindow){
						infowindow = new google.maps.InfoWindow();		
					}
				
					infowindow.setPosition(e.latLng);
					infowindow.setContent(title);
					infowindow.open(map);
				   
					for (var i = 0; i < polylines.length; i++){
						polylines[i].setOptions({strokeWeight: POLYLINE_STROKEWEIGHT});
					}
				   
					polyline.setOptions({strokeWeight: POLYLINE_STROKEWEIGHT_SELECTED});
				});

				// Close the InfoWindow on mouseout:
				//google.maps.event.addListener(polyline, 'mouseout', function() {
				//   infowindow.close();
				//});
			}
		}

		var drawMovingCloserOrFurther = function(){
			polylines = [];
			var polylines_ = [];
			
			var sign_prev = 1;
			
			var dist_prev = 0;
			
			for(var i = 0; i < path.length; i++){
				var sign = 1;
			
				if (path[i].dist > dist_prev){
					sign = 1;
				} else if (path[i].dist < dist_prev){
					sign = -1;
				} else {
					sign = sign_prev;
				}
				
				if (sign != sign_prev || i == 0){
					polylines_.push({path: [], sign: sign});
					
					if (i > 0){
						polylines_[polylines_.length-1].path.push(path[i-1]);
					}
					sign_prev = sign;
				}
				
				dist_prev = path[i].dist;
				
				polylines_[polylines_.length-1].path.push(path[i]);
			}
				
			for(var j = 0; j < polylines_.length; j++){
				var strokeColor = "#E74C3C";
				
				if (polylines_[j].sign == -1){
					strokeColor = "#28B463";
				}
				
				var title = "";
				if (polylines_[j].sign == -1){
					title += "Approaching";
				} else {
					title += "Leaving";		
				}
				title += " home (" + polylines_[j].path[0].dist/1000 + " km -> " + polylines_[j].path[polylines_[j].path.length - 1].dist/1000 + " km)"; 
			
				drawPolyline(polylines_[j].path,strokeColor,title);
			}	
		}

		function decodeLevels(encodedLevelsString) {
			var decodedLevels = [];

			for (var i = 0; i < encodedLevelsString.length; ++i) {
				var level = encodedLevelsString.charCodeAt(i) - 63;
				decodedLevels.push(level);
			}
			return decodedLevels;
		}

		var calcDistanceFromHome = function(path,map){
			home = path[0];
			furthest = null;
			
			var dist_max = 0;
			
			for(var i = 0; i < path.length; i++){
				var dist = geolib.getDistance(home, path[i], 0, 0);
				path[i].dist = dist;
				
				if (dist > dist_max){
					dist_max = dist;
					furthest = path[i];
				}
			}
			
			/*home*/
			var markerHome = new google.maps.Marker({
				position: new google.maps.LatLng(home.latitude,home.longitude),
				icon: "_icons/home2.png",
				map: map/*,
				title: 'Home'*/
			});
			
			var infowindowHome = new google.maps.InfoWindow({
				content: 'Home'
			});
			
			infowindowHome.open(map,markerHome);
			
			markerHome.addListener('click', function() {
				infowindowHome.open(map, markerHome);
			});
			
			/*furthest point*/
			var title = "Furthest point: " + dist_max/1000 + " km from home";
			
			var markerFurthest = new google.maps.Marker({
				position: new google.maps.LatLng(furthest.latitude,furthest.longitude),
				icon: "_icons/furthest2.png",
				map: map/*,
				title: title*/
			});
			
			var infowindowFurthest = new google.maps.InfoWindow({
				content: title
			});
			
			infowindowFurthest.open(map,markerFurthest);
			
			markerFurthest.addListener('click', function() {
				infowindowFurthest.open(map, markerFurthest);
			});
		}

		var decodePolyline = function(str, precision) {
			var index = 0,
				lat = 0,
				lng = 0,
				coordinates = [],
				shift = 0,
				result = 0,
				byte = null,
				latitude_change,
				longitude_change,
				factor = Math.pow(10, precision || 5);

			// Coordinates have variable length when encoded, so just keep
			// track of whether we've hit the end of the string. In each
			// loop iteration, a single coordinate is decoded.
			while (index < str.length) {

				// Reset shift, result, and byte
				byte = null;
				shift = 0;
				result = 0;

				do {
					byte = str.charCodeAt(index++) - 63;
					result |= (byte & 0x1f) << shift;
					shift += 5;
				} while (byte >= 0x20);

				latitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

				shift = result = 0;

				do {
					byte = str.charCodeAt(index++) - 63;
					result |= (byte & 0x1f) << shift;
					shift += 5;
				} while (byte >= 0x20);

				longitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

				lat += latitude_change;
				lng += longitude_change;

				coordinates.push({latitude: lat / factor, longitude: lng / factor});
			}

			return coordinates;
		};

		var createPolylinePath = function(_path){
			var _polylinePath = [];

			for(var i = 0; i < _path.length; i++){
				_polylinePath.push(new google.maps.LatLng(_path[i].latitude,_path[i].longitude));
			}
			
			return _polylinePath;
		}
		
		getActivities();
			
		google.charts.load("current", {packages:["calendar"]});
			
		/*google.charts.setOnLoadCallback(function(){;
			dtActivities = new google.visualization.DataTable();		   
			dtActivities.addColumn({ type: 'date', id: 'Date' });		   
			dtActivities.addColumn({ type: 'number', id: 'Distance' });
			dtActivities.addColumn({ type:'string', role:'tooltip'}); 

		});*/
		
		var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
	}
})();

