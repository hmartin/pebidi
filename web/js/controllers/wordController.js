/*global URL */
/*global API_URL */
/*global app */
app.controller('WordCtrl', function($scope, $routeParams, $http, wordService) {

    $scope.word = $routeParams.word;
    wordService.get($routeParams.word).then(function(data) {
        $scope.wordSenses = data;
    });
});