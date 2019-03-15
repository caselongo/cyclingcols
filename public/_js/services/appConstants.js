// AppConstants.js
(function () {
	'use strict';
	// Constanten in deze app
	angular.module('homeSweatHome')
		.constant('STRAVA', {
			client_id: 8752,
			client_secret: '149ff5792ba900da9a5beb578a03051dbb0cf3ba',
			auth_uri_dev: "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=http://localhost/HomeSweatHome/#/&approval_prompt=force",
			auth_uri: "https://www.strava.com/oauth/authorize?client_id=8752&response_type=code&redirect_uri=https://www.cyclingcols.com/homesweathome.htm/#/&approval_prompt=force",
			token_uri: "https://www.strava.com/oauth/token",
			deauth_uri: "https://www.strava.com/oauth/deauthorize"
		})
		.constant('STATUSCODE', {
			ONHOLD: 1,
			CORRECTIONNEEDED: 2,
			CORRECTED: 4,
			UPDATENEEDED: 8,
			UPDATED: 16
		})
})();