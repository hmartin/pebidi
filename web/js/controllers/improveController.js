app
    .controller('ImproveCtrl', function ($scope, $routeParams, $location) {
        console.log($routeParams.word);
      
        $scope.word = $routeParams.word;

        $scope.improveWord = function () {
            
        }
    })
;
