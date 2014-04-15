app.controller('HomeCtrl', function ($scope, $http, $location, $cookies) {
    if ($cookies.dic) {
        $scope.dic = angular.fromJson($cookies.dic);
        $location.path('/addWord/' + $scope.dic.id);
    }
    $scope.processForm = function () {
        $http.post(API_URL + 'emails/onlies.json', $scope.formData).success(function (data) {
            if (data.dic) {
                $cookies.dic = angular.toJson(data.dic);
                $location.path('/addWord/' + data.dic.id);
            }
        });
    };
})


    .controller('WordCtrl', function ($scope, $http, $location, $cookies, wordRetriever){
        $scope.words = [];

        // gives another movie array on change
        $scope.updateMovies = function(typed){
            // MovieRetriever could be some service returning a promise
            $scope.newWords = wordRetriever.getWords(typed);
            $scope.newWords.then(function(data){
              $scope.words = data;
            });
        }
        
        $scope.processWord = function () {
            $scope.formData.id = $scope.dic.id;
            $http.post(API_URL + 'news/words.json', $scope.formData).success(function (data) {
                $scope.dic = data.dic;
                $cookies.dic = angular.toJson(data.dic);
                $scope.formData.word = '';
                $scope.formData.translation = '';
            });
        };
    })
    .controller('TestCtrl', function ($scope, $http, $location, $cookies) {
        $scope.processForm = function () {
            $scope.formData.id = $scope.dic.id;
            $http.post(API_URL + 'creates/tests.json', $scope.formData).success(function (data) {
                $scope.words = data.words;
            });
        };

    })

    .controller('DictionnaryCtrl', function ($scope, $http, $location, $cookies) {
        $http.post(API_URL + 'words.json', $cookies.dic).success(function (data) {
            $scope.words = data;
        });
    })

    .controller('rootCtrl', function ($scope, $http, $cookies, $translate, $location) {
        $scope.changeLanguage = function (key) {
            $translate.use(key);
        };
        $scope.clearCookies = function () {
            delete $cookies['dic'];
            $location.path('/');
        };
        $scope.$watch(function () {
            return $cookies.dic;
        }, function (newValue) {
            $scope.dic = angular.fromJson($cookies.dic);
        });
    })