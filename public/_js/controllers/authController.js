(function () {
	'use strict';

	angular.module('homeSweatHome')
		.controller('authController', authController);
		
	authController.$inject = ['$location','STRAVA','$window'];

	function authController($location,strava,$window) {
		var vm = this;
		
		vm.connect = function(){
			var uri = strava.auth_uri;
			if ($location.$$host == 'localhost'){
				uri = strava.auth_uri_dev;
			}
			
			$window.location.href = uri;
		}
	}
})();