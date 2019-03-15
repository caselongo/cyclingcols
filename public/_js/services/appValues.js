// AppValue.js
(function () {
	'use strict';
	angular.module('homeSweatHome')
        .value('strava_athlete',{
			username: null
		})
        .value('strava_access_token',{
			value: null
		});
})();