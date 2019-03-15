(function () {
    'use strict';
	var app = angular.module('homeSweatHome', ['ui.router','angularSpinner']);

	app
	.config(function($stateProvider,$urlRouterProvider/*,$locationProvider*/) {
		// For any unmatched url, redirect to /
		$urlRouterProvider.otherwise("/");
	  
		$stateProvider
		.state('process', {
			url: '/',  
			templateUrl: '_views/process.html',
			controller: 'processController',
			controllerAs: 'ctrl'
		})
		.state('auth', {
			url: '/auth',  
			templateUrl: '_views/auth.html',
			controller: 'authController',
			controllerAs: 'ctrl'
		})
		.state('home', {
			url: '/home',  
			templateUrl: '_views/home.html',
			controller: 'homeController',
			controllerAs: 'ctrl'
		})
		
		//$locationProvider.html5Mode(true);
	})
	
})();