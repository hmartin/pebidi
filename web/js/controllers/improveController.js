app
    .controller('ImproveCtrl', function ($scope, $routeParams, $location) {
        console.log($routeParams.word);
        $scope.word = $routeParams.word;

    })
    .controller('WordCtrl', function ($scope, $routeParams, $http) {
        $http
            .get(API_URL + 'words/'+$routeParams.id +'.json')
            .success(function (data) {
                $scope.wordSenses = data;
            });

    })

;
