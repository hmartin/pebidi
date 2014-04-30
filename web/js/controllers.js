app
    .controller('HomeCtrl', function ($scope, $http, $location, $cookies) {
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
        $scope.getWords = function(val) {
            return $http.get(API_URL + 'auto/complete/words.json', {
                params: {
                    word: val
                }
            }).then(function(res){
                    var words = [];
                    angular.forEach(res.data.words, function(item){
                        words.push(item.word);
                    });
                    return words;
                });
            };

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

    .controller('CreateTestCtrl', function ($scope, testService) {
        $scope.nbquestion = 20;
        $scope.startTest = testService.createTest($scope.dic.id, $scope.nbquestion);
    })

    .controller('TestCtrl', function ($scope, $rootScope, $http, $location, $cookies, testService) {
        $scope.step = 1;
        $scope.i = 0;
        
        $scope.words = testService.words;
        $scope.word = $scope.words[$scope.i];
        
        $scope.getAnswer = function () {
            $scope.step = 2;        
        };
        $scope.saveResult = function () {
            $scope.i++
            $scope.word = $scope.words[$scope.i];
            $scope.step = 1;
            if ($scope.i == $scope.nbquestion) {
                testsService.saveResults();
                //saveResult
                //redirect congrat
            }
            $scope.progress = $scope.i * 100 / $scope.nbquestion;
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