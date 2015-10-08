/*global URL */
/*global API_URL */
/*global app */
app.controller('WordCtrl', function($scope, $routeParams, $http, wordService) {
    
    wordService.get($routeParams.word).then(function(data) {
        $scope.word = data[0];
        $scope.wordSenses = data;
    });
    
});