(function () {
	'use strict';

	angular.module('homeSweatHome')
		.controller('mainController', mainController);
		
	mainController.$inject = ['strava_athlete','strava_access_token','processService'];

	function mainController(strava_athlete,strava_access_token,processService) {
		var vm = this;
		
		vm.strava_athlete = strava_athlete;
		
		vm.disconnect = function(){
			processService.disconnect(strava_access_token)
				.then(function(data){
					strava_access_token = null;
					strava_athlete.username = null;
					$location.path("/auth");
				},
				function(errorData){
					
				}			
			);	
		}
	}
})();