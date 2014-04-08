var mainControllers = angular.module('mainControllers', []);


mainControllers.controller('HomeCtrl', ['$scope', '$http','$location',
    function($scope, $http, $location) {
        $scope.processForm = function() {
            $http.post(API_URL + 'emails/onlies.json', $scope.formData).success(function(data) {
                if(data.id) {
                    $location.path('/addWord/'+data.id);
                }


            });
        };
    }
]);