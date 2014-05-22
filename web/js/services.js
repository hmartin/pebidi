new app
    .service('dictionaryService', function () {
        var dictionary;

        this.set = function (dic) {
            dictionary = dic;
        };
        this.get = function () {
            return dictionary;
        };
    })

    .service('testService', function ($http, $location, $cookies, userService) {
        this.words = {};
        this.nbQuestion = 0;
        this.id = 0;
        this.did = 0;
        this.score = 0;

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
                    this.globalScore = data.globalScore;
                    userService.getScore(this.did);
                }.bind(this));
            s = 0;i = 0;
            points.forEach(function(element) {
                this.score = this.score + element.pt;
                i = i+1;
            });
            this.score = this.score * 100 / i;
        }
        
        this.doItAgain = function () {
            $http.post(API_URL + 'creates/tests.json', {id: id, type: 'doItAgain'})
                .success(function (data) {
                    $location.path('/questions');
                }.bind(this));
        }
    })

    .service('userService', function ($http, $location, $cookies) {
        this.getScore = function (did) {
            $http.get(API_URL + 'score.json', {params:{did: did, uid: $cookies.uid}})
                .success(function (data) {
                    $cookies.score = angular.toJson(data.score);
                }.bind(this));
        }
    })

    .service('wordRetriever', function () {
        this.getWords = function (typed) {
            $http.post(API_URL + 'get/tests.json', {'typed': typed}).success(function (data) {
                return data.words;
            });
        };
    })

    .service('dicService', function ($http, $location, $cookies) {

        this.create = function (originLang, destLang) {
            $http.post(API_URL + 'creates/dics.json', {uid: $cookies.uid, originLang: originLang, destLang: destLang}).success(function (data) {
                if (data.dic) {
                    $cookies.dic = angular.toJson(data.dic);
                    $location.path('/addWord/' + data.dic.id);
                }
            });
        };
        this.get = function (id) {
            $http.post(API_URL + 'gets/dics.json', {id: id}).success(function (data) {
                if (data.dic) {
                    $cookies.dic = angular.toJson(data.dic);
                    return data.dic;
                }
            });
        };
    });