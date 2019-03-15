(function () {
	'use strict';

	angular.module('homeSweatHome')
		.controller('processController', processController);
		
	processController.$inject = ['$location','strava_access_token','strava_athlete','processService'];

	function processController($location,strava_access_token,strava_athlete,processService) {
		
		var getParameterByName = function(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}		
		
		var vm = this;
		vm.wrong = false;
	
		if (strava_access_token.value){
			//$location.search('code',null);
			$location.path("/home");
			//$location.url("localhost/HomeSweatHome/#/home");
		}
		
		var code = getParameterByName("code");
		
		if (!code){
			$location.path("/auth");
		} else {
			processService.getToken(code)
				.then(function(data){
					strava_access_token.value = data.access_token;
					strava_athlete.username = data.athlete.username;
					//$location.search('code',null);
					$location.path("/home");
					//$location.url("localhost/HomeSweatHome/#/home");
				},
				function(errorData){
					vm.wrong = true;
				}			
			);			
		}

	}
})();