app
    .service('mainService', function () {
        this.main = {};
        this.dic ={};
        this.lang = '';

        this.setDic = function (dic) {
            this.dic = dic;
            LocalStorageModule.set('dic', dic);
        };
        this.setDicScore = function (dicScore) {
            main.dic.score = dicScore;

        };
        this.setCountWord = function (countWord) {
            main.dic.countWord = countWord;

        };
        this.getDic = function () {
            return this.dic;
        };
    })

    .service('testService', function ($http, $location, mainService) {
        this.words = {};
        this.nbQuestion = 0;
        this.id = 0;
        this.did = 0;
        this.testScore = 0;

        this.createTest = function (did, question) {
            this.nbQuestion = question;
            this.did = did;
            $http.post(API_URL + 'creates/tests.json', {uid: $cookies.uid, id: did, nbQuestion: question, type: 'n'})
                .success(function (data) {
                    this.words = data.words;
                    this.id = data.id;
                    $location.path('/questions');
                }.bind(this));
        }
        this.saveResults = function (points) {
            $http.post(API_URL + 'saves/results.json', {id: this.id, points: points})
                .success(function (data) {
                    mainService.setScore(data.globalScore);
                }.bind(this));
            s = 0;i = 0;
            this.testScore = points.reduce(function(a, b) {
                return a + b.p;
            }, 0);
            this.testScore = this.testScore * 100 / this.nbQuestion;
        }
        
        this.doItAgain = function () {
            $http.post(API_URL + 'creates/tests.json', {id: id, type: 'doItAgain'})
                .success(function (data) {
                    $location.path('/questions');
                }.bind(this));
        }
        this.getTestScore = function () {
            console.log('getTestScore ');
            return this.testScore;
        }
    })

    .service('userService', function ($http, $location) {
        /* USELESS UPDATE SCORE AFTER AJAX CALL
         * this.getScore = function (did) {
            $http.get(API_URL + 'score.json', {params:{did: did, uid: $cookies.uid}})
                .success(function (data) {
                    $cookies.score = angular.toJson(data.score);
                }.bind(this));
        }*/
    })

    .service('wordRetriever', function () {
        this.getWords = function (typed) {
            $http.post(API_URL + 'get/tests.json', {'typed': typed}).success(function (data) {
                return data.words;
            });
        };
    })

    .service('dicService', function ($http, $location, mainService) {

        this.create = function (originLang, destLang) {
            $http.post(API_URL + 'creates/dics.json', {uid: $cookies.uid, originLang: originLang, destLang: destLang}).success(function (data) {
                if (data.dic) {
                    mainService.setDic(data.dic);
                    $location.path('/addWord/' + data.dic.id);
                }
            });
        };
        this.get = function (id) {
            $http.post(API_URL + 'gets/dics.json', {id: id}).success(function (data) {
                if (data.dic) {
                    mainService.setDic(angular.toJson(data.dic));
                }
            });
        };
    });