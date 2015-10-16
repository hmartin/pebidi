/*global URL */
/*global API_URL */
/*global app */
app
    .controller('ImproveCtrl', function ($scope, $routeParams, $compile, wordService) 
    {
        $scope.word = $routeParams.word;
        wordService.get($routeParams.word).then(function (data) {
            $scope.wordSenses = data;
        });

        $scope.addTransaltion = function() {
            $scope.wordSenses.push({'w' : $scope.word});
        };

        $scope.improve = function () {
            console.log($scope.wordSenses);
            wordService.improve($scope.wordSenses);
        };
    })
;
