app
    .controller('HomeCtrl', function ($scope, $http, $location, localStorageService, mainService, pediService) {
        $scope.lang = ['en', 'es', 'fr'];
        $scope.count = $scope.lang.lenght;
        $scope.ip = 0;
        $scope.ia = 2;
        if (localStorageService.get('dic') && localStorageService.get('uid')) {
            mainService.setDic(localStorageService.get('dic'));
            console.log(mainService.dic);
            $location.path('/dictionary/' + mainService.dic.id);
        }
        $scope.processForm = function () {
            $http.post(API_URL + 'emails.json', $scope.formData).success(function (data) {
                mainService.setUid(data.uid);
                if (data.hasOwnProperty('dic')) {
                    console.info(data.dic)
                    mainService.setDic(data.dic);
                } else {
                    pediService.create($scope.lang[$scope.ip], $scope.lang[$scope.ia]);
                }
            });
            $location.path('/dictionary');
        };
    })

    .controller('WordCtrl', function ($scope, $http, $location, $routeParams, $filter, pediService, mainService, wordService, dicService, localStorageService) {
        $scope.formData = {};

        if (($scope.dic && $routeParams.id != $scope.dic.id) || !$scope.dic) {
            pediService.get($routeParams.id);
        }
        dicService.loadDic();

        $scope.getWords = function (val) {
            sug = $filter('limitTo')($filter('filter')(dicService.getDic(), val, function (actual, expected) {
                return actual.toString().toLowerCase().indexOf(expected.toLowerCase()) == 0;
            }), 10);
            $scope.submitCreate = false;
            if (sug.length == 0) {
                $scope.submitCreate = true;
            }

            return sug;
        };

        $scope.processWord = function () {
            $scope.formData.id = $scope.dic.id;
            console.log($scope.formData);
            wordService.post($scope.formData);
            $scope.formData.word = '';
            $scope.formData.translation = '';
        };
    })

    .controller('CreateTestCtrl', function ($scope, testService) {

        if ($scope.dic.countWord > 20) {
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
        }
        $scope.startTest = function () {
            testService.createTest($scope.dic.id, $scope.nbquestion);
        }
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
            $scope.i++;
            $scope.progress = ($scope.i) * 100 / testService.nbQuestion;
            $scope.points.push({wid: $scope.word.id, p: p});

            if ($scope.i == testService.nbQuestion) {
                testService.saveResults($scope.points);
                $location.path('/congrats');
            }

            $scope.word = $scope.words[$scope.i];
            $scope.step = 1;
        };
    })

    .controller('CongratsTestCtrl', function ($scope, testService) {
        $scope.doItAgain = function () {
            testService.doItAgain();
        }
        $scope.testScore = testService.getTestScore();
    })

    .controller('AddGroupWordCtrl', function ($scope, $http, $location, mainService) {
        $http
            .get(API_URL + 'words/group.json', {params: {lang: 'en'}})
            .success(function (data) {
                $scope.groupsWords = data.groups;
            });

        $scope.addGroupWord = function (id) {
            $scope.data = {};
            $scope.data.did = $scope.dic.id;
            $scope.data.gwid = id;
            $http.post(API_URL + 'adds/groups/words.json', $scope.data).success(function (data) {
                mainService.setDic(data.dic);
            });
        };

        $scope.viewGroupWord = function (id) {
            $location.path('/viewWords/group/' + id);
        };
    })

    .controller('DictionaryCtrl', function ($scope, $http, $route, $routeParams, wordService, pediService) {

        if (($scope.dic && $routeParams.id != $scope.dic.id) || !$scope.dic) {
            pediService.get($routeParams.id);
        }


        pediService.getWords($route.current.type, $routeParams.id).then(function (data) {
            console.log(data);
            $scope.words = data;
        });

        $scope.deleteWord = function (id) {
            wordService.delete(id);
        }
    })

    .controller('rootCtrl', function ($scope, $rootScope, $http, localStorageService, $translate, $location, mainService) {

        $scope.service = mainService;
        $scope.changeLanguage = function (key) {
            $translate.use(key);
            mainService.lang = key;
        };

        if (mainService.getUid()) {
            $scope.uid = mainService.getUid();
        }

        $scope.clearLocalStorage = function () {
            mainService.setDic({});
            mainService.setUid(0);
            localStorageService.clearAll();
            $location.path('/');
        };

        $scope.dic = mainService.getDic();

        $scope.$watch('service.getDic()', function (data) {
            console.info('wathed!');
            console.log(data.score);
            $scope.dic = mainService.getDic();
        }, true);

        $scope.$watch('service.watchUid()', function (data) {
            $scope.uid = data;
        }, true);

    })
