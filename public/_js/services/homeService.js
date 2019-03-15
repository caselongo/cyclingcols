(function () {
	'use strict';

	// Definitie van de personFactory
	angular.module('homeSweatHome')
		.service('homeService', homeService);
		
	homeService.$inject = ['STRAVA','utilityService'];

	// Implementatie van personService
	function homeService(STRAVA,utilityService) {
	
		this.getActivity = function(url){		
			return utilityService.getPromise("GET",url);
		}
		
		this.getActivities = function(url){		
			return utilityService.getPromise("GET",url);
		}
	
		this.disconnect = function(access_token){		
			return utilityService.getPromise("POST",STRAVA.deauth_uri,{},{
				access_token: access_token
			});
		}
	}
})();
