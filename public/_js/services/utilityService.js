(function () {
	'use strict';

	angular.module('homeSweatHome')
		.service('utilityService', utilityService);
		
	utilityService.$inject = ['$http','$q'];

	function utilityService($http,$q) {				
		this.getPromise = function(httpMethod,url,params,data,dataModifier){
			var deferred = $q.defer();
			$http({
				url: url,
				method: httpMethod,
				params: params,
				data: data
			}).success(function(data,status,header,config){
				if (dataModifier){
					data = dataModifier(data);
				}
				
				deferred.resolve(data);	
			}).error(function(err){
				deferred.reject(err);
			});
			
			return deferred.promise;		
		}

	}
})();
