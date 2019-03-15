(function () {
	'use strict';

	// Definitie van de personFactory
	angular.module('homeSweatHome')
		.service('processService', processService);
		
	processService.$inject = ['STRAVA','utilityService'];

	// Implementatie van personService
	function processService(STRAVA,utilityService) {
	
		this.getToken = function(code){		
			return utilityService.getPromise("POST",STRAVA.token_uri,{},{
				client_id: STRAVA.client_id,
				client_secret: STRAVA.client_secret,
				code: code
			});
		}
	
		this.disconnect = function(access_token){		
			return utilityService.getPromise("POST",STRAVA.deauth_uri,{},{
				access_token: access_token
			});
		}
	}
})();
