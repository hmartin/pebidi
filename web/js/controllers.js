/*global API_URL */
/*global URL */
/*global app */
/*global ENV */
app
    .controller('HomeCtrl', function ($scope, $http, $location, localStorageService, mainService, pediService) {
        $scope.lang = ['en', 'es', 'fr'];
        $scope.count = $scope.lang.length;
        $scope.ip = 0;
        $scope.ia = 2;
        if (localStorageService.get('dic') && localStorageService.get('user')) {
            $http.get(API_URL + 'users/' + localStorageService.get('user').id).success(function (data) {
                mainService.setUser(data.user);
                mainService.setDic(data.dic);
            });
            $location.path('/dictionary/' + mainService.getDic().id);
        }
        $scope.processForm = function () {
            $http.post(API_URL + 'users/emails', $scope.formData).success(function (data) {
                console.log(data);
                mainService.setUser(data.user);
                mainService.setDic(data.dic);
            });
            $location.path('/dictionary');
        };
    })

    .controller('CreateTestCtrl', function ($scope, mainService, testService) {
        // TODO do test for group
        if (true) {
            mainService.setDic(mainService.getUser().dic);
        }

        if ($scope.user.dic.countWord > 20) {
            $scope.nbquestion = 20;
        } else {
            $scope.nbquestion = $scope.dic.countWord;
        }

        $scope.changeNbQuestion = function (n) {
            $scope.nbquestion = $scope.nbquestion + n;
            if ($scope.nbquestion < 1) {
                $scope.nbquestion = 1;
            } else if ($scope.nbquestion > $scope.dic.countWord - 1) {
                $scope.nbquestion = $scope.dic.countWord;
            }
        };
        $scope.startTest = function () {
            testService.createTest($scope.dic.id, $scope.nbquestion);
        };
    })

    .controller('TestCtrl', function ($scope, $http, $location, testService) {
        $scope.step = 1;
        $scope.points = [];
        $scope.i = 0;
        $scope.progress = 0;

        $scope.words = testService.words;

        $scope.word = $scope.words[$scope.i];

        $scope.getAnswer = function () {
            $scope.step = 2;
        };
        $scope.saveResult = function (p) {
            var j = $scope.i + 1;
            $scope.points.push({wid: $scope.word.id, p: p});

            if (j == testService.nbQuestion) {
                testService.saveResults($scope.points);
                $location.path('/congrats');
            }

            $scope.i = j;
            $scope.progress = ($scope.i) * 100 / testService.nbQuestion;

            $scope.word = $scope.words[$scope.i];
            $scope.step = 1;
        };
    })

    .controller('CongratsTestCtrl', function ($scope, testService) {
        $scope.doItAgain = function () {
            testService.doItAgain();
        };
        $scope.testScore = testService.getTestScore();
    })

    .controller('WordListCtrl', function ($scope, $http, $route, $routeParams, wordService, pediService) {

        if (($scope.dic && $routeParams.id != $scope.dic.id) || !$scope.dic) {
            pediService.get($routeParams.id);
        } else {
            $scope.dic = $scope.user.dic
        }

        pediService.getWords($routeParams.id).then(function (data) {
            $scope.words = data;
        });

        $scope.deleteWord = function (word) {
            $scope.words.splice($scope.words.indexOf(word), 1);
            pediService.delete(word.id);
        };
    })

    .controller('rootCtrl', function ($scope, $rootScope, $http, localStorageService, $translate, $location, mainService) {

        $scope.env = ENV;

        $scope.changeLanguage = function (key) {
            $translate.use(key);
            mainService.lang = key;
        };

        if (mainService.getUid()) {
            $scope.uid = mainService.getUid();
        }

        $scope.clearLocalStorage = function () {
            mainService.setDic(null);
            mainService.setUser(null);
            $scope.user = {};
            $scope.dic = {};
            localStorageService.clearAll();
            $location.path('/');
        };

        $scope.$watch(mainService.getDic, function (data) {
            console.info('wathed!');
            $scope.dic = mainService.getDic();
        }, true);

        $scope.$watch(mainService.getUser, function (data) {
            console.info('mainService getUser');
            $scope.user = mainService.getUser();
        }, true);

    })
