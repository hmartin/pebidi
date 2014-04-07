var mainControllers = angular.module('mainControllers', []);

mainControllers.controller('HomeCtrl', ['$scope','$http',
  function($scope,$http) {
    $scope.test = 'ok';
      
			$scope.processForm = function() {
                $http.post('http://pebidi.com/app_dev.php/betaUse', $scope.formData)
		.success(function(data) {
			
		});
			};
  }]
);
