app
    .controller('HomeCtrl', function ($scope, $http, $location, $cookies) {
        if ($cookies.dic && $cookies.uid) {
            $scope.dic = angular.fromJson($cookies.dic);
            $location.path('/addWord/' + $scope.dic.id);
        }
        $scope.processForm = function () {
            $http.post(API_URL + 'emails.json', $scope.formData).success(function (data) {
                $cookies.uid = angular.toJson(data.uid);
                if (data.hasOwnProperty('dic')) {
                    $cookies.dic = angular.toJson(data.dic);
                    $location.path('/addWord/' + data.dic.id);
                } else {
                    $location.path('/createDic/');
                }
            });
        };
    })

    .controller('CreateDicCtrl', function ($scope, $http, $location, $cookies, dicService) {
        $scope.lang = ['de', 'en', 'es', 'fr'];
        $scope.count = $scope.lang.lenght;
        $scope.ip = 0;
        $scope.ia = 1;
        //type, orign Lang, dest Lang
        $scope.createDic = function () {
            dicService.create($scope.lang[$scope.ip], $scope.lang[$scope.ia]);
        };
    })

    .controller('WordCtrl', function ($scope, $http, $location, $cookies, $routeParams, dicService, userService){
        $scope.formData = {};
        if (($scope.dic && $routeParams.id != $scope.dic.id) || !$scope.dic) {
            dicService.get($routeParams.id);
        }

        $scope.$watch('dic', function() {
            userService.getScore($scope.dic.id);
        });

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
        if ( $scope.dic.countWord > 20) {
            $scope.nbquestion = 20;
        } else {
            $scope.nbquestion = $scope.dic.countWord;
        }
        
        $scope.changeNbQuestion = function (n) {
            $scope.nbquestion = $scope.nbquestion + n;
            if ($scope.nbquestion < 1 ) {
                $scope.nbquestion = 1;
            } else if ($scope.nbquestion > $scope.dic.countWord-1) {
            $scope.nbquestion = $scope.dic.countWord;
            }
        }
        $scope.startTest = function () {
            testService.createTest($scope.dic.id, $scope.nbquestion);
        }
    })

    .controller('TestCtrl', function ($scope, $rootScope, $http, $location, $cookies, testService) {
        $scope.step = 1;
        $scope.points = [];
        $scope.i = 0;$scope.progress = 0;

        $scope.words = testService.words;

        $scope.word = $scope.words[$scope.i];

        $scope.getAnswer = function () {
            $scope.step = 2;
        };
        $scope.saveResult = function (p) {
            $scope.i++;
            $scope.progress = ($scope.i) * 100 / testService.nbQuestion;
            $scope.point = {};$scope.point.wid = $scope.word.id;$scope.point.p=p;
            $scope.points.push($scope.point);

            if ($scope.i == testService.nbQuestion) {
                console.log($scope.points);
                testService.saveResults($scope.points);
                $location.path('/congrats');
            }
            console.log(testService.nbQuestion);

            $scope.word = $scope.words[$scope.i];
            $scope.step = 1;

        };
    })

    .controller('CongratsTestCtrl', function ($scope, testService) {
    })

    .controller('AddGroupWordCtrl', function ($scope, $http, $location, $cookies) {
        $http
            .get(API_URL + 'words/group.json', { params: { lang: 'en' } })
            .success(function(data){
                    $scope.groupsWords = data.groups;
            });
        
        $scope.addGroupWord = function (id) {
            $scope.data = {};
            $scope.data.did = $scope.dic.id;
            $scope.data.gwid = id;
            $http.post(API_URL + 'adds/groups/words.json', $scope.data).success(function (data) {
                // success
            });
        };
        
        $scope.viewGroupWord = function (id) {
            $location.path('/viewWords/group/' + id);
        };
    })

    .controller('DictionnaryCtrl', function ($scope, $http, $location, $cookies) {
        if ($routeParams.type) {
        $http
        .get(API_URL + 'words/group.json', { params: { id: $routeParams.id, type : $routeParams.type } })
            .success(function(data){
                    $scope.words = data.words;
            });
            
        } 
        else {
        $http.post(API_URL + 'words.json', $cookies.dic).success(function (data) {
            $scope.words = data;
        });            
        }
    })

    .controller('rootCtrl', function ($scope, $http, $cookies, $translate, $location) {
        $scope.changeLanguage = function (key) {
            $translate.use(key);
            $cookies.lang = key;
        };
        $scope.clearCookies = function () {
            delete $cookies['dic'];
            delete $cookies['uid'];
            $location.path('/');
        };
        $scope.$watch(function () {
            return $cookies.dic;
        }, function (newValue) {
            $scope.dic = angular.fromJson($cookies.dic);
        });
        $scope.$watch(function () {
            return $cookies.uid;
        }, function (newValue) {
            $scope.uid = angular.fromJson($cookies.uid);
        });
        $scope.$watch(function () {
            return $cookies.score;
        }, function (newValue) {
            $scope.score = angular.fromJson($cookies.score);
        });
    })