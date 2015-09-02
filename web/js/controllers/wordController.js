app.controller('WordCtrl', function($scope, $routeParams, $http) {
    console.log($routeParams.id);
    $http.get(API_URL + 'words/' + $routeParams.id).success(function(data) {
        $scope.word = data[0];
        $scope.wordSenses = data;
    });
});