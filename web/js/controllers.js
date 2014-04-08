var mainControllers = angular.module('mainControllers', []);


mainControllers.controller('HomeCtrl', ['$scope', '$http',
    function($scope, $http) {
        $scope.processForm = function() {
            $http.post(API_URL + '/betaUse', $scope.formData).success(function(data) {});
        };
    }
]);