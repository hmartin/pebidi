/*global URL */
/*global API_URL */
/*global app */
app.controller('WordCtrl', function($scope, $routeParams, $http, wordService) {

    $scope.word = $routeParams.word;
    $scope.wordSenses = wordService.get($routeParams.word);
    
});