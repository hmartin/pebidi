app
    .service('mainService', function (localStorageService) {
        this.main = {};
        this.dic = {};
        this.lang = '';

        this.setDic = function (dic) {
            this.dic = dic;
            localStorageService.set('dic', dic);
            console.log(dic);
        };
        this.setGlobalScore = function (dicScore) {
            this.dic.score = dicScore;

        };
        this.setCountWord = function (countWord) {
            this.dic.countWord = countWord;
        };
        this.getDic = function () {
            if(!('id' in this.dic) && (localStorageService.get('dic'))) {
                this.dic = localStorageService.get('dic');
            }
            return this.dic;
        };

        this.getDid = function () {
            return this.dic.id;
        };

        this.setUid = function (uid) {
            this.uid = uid;
            localStorageService.set('uid', uid);
        };
        this.getUid = function () {
            if (this.uid || localStorageService.get('uid')) {
                if (!this.uid) {
                    this.uid = localStorageService.get('uid');
                }
                return this.uid;
            } else {
                return false;
            }
        };
        this.watchUid = function () {
            return this.uid;
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
            $http.post(API_URL + 'creates/tests.json', {uid: mainService.getUid(), id: did, nbQuestion: question, type: 'n'})
                .success(function (data) {
                    this.words = data.words;
                    this.id = data.id;
                    $location.path('/questions');
                }.bind(this));
        }
        this.saveResults = function (points) {
            $http.post(API_URL + 'saves/results.json', {id: this.id, points: points})
                .success(function (data) {
                    mainService.setGlobalScore(data.score);
                }.bind(this));
            s = 0;
            i = 0;
            this.testScore = points.reduce(function (a, b) {
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

    .service('wordService', function ($http, $rootScope, $timeout, mainService) {
        this.post = function (formData) {
            console.log(formData.word);
            console.log('??');
            $http.post(API_URL + 'news/words.json', {'word' : formData.word, 'translation': formData.translation, 'id': mainService.getDid()}).success(function (data) {
                $timeout(function () {
                    mainService.setDic(data.dic);
                    $rootScope.$apply();
                }, 0);
            });
        };

        this.delete = function(id) {
            $http.post(API_URL + 'deletes/words.json', { 'id': id, 'did': mainService.getDid()}).success(function (data) {
                mainService.setDic(data.dic);

            });
        }
    })

    .service('wordRetriever', function ($http) {
        this.getWords = function (typed) {
            $http.post(API_URL + 'get/tests.json', {'typed': typed}).success(function (data) {
                return data.words;
            });
        };
    })

    .service('dicService', function ($http, $rootScope, $location, $timeout, mainService) {

        this.create = function (originLang, destLang) {
            $http.post(API_URL + 'creates/dics.json', {uid: mainService.getUid(), originLang: originLang, destLang: destLang}).success(function (data) {
                if (data.dic) {
                    mainService.setDic(data.dic);
                    $location.path('/addWord/' + data.dic.id);
                }
            });
        };
        this.get = function (id) {
            data = {'id': id};
            if (mainService.getUid()) {
                data.uid = mainService.getUid();
            }
            $http.post(API_URL + 'gets/dics.json', data).success(function (data) {
                if (data.dic) {
                    $timeout(function () {
                        console.log('timeout');
                        mainService.setDic(data.dic);
                        $rootScope.$apply();
                    }, 0);
                }
            })
        };

        this.getWords = function (type, id) {
            var promise = $http
                .get(API_URL + 'types/' + type + '/words/' + id + '/list.json', { params: {'uid' : mainService.getUid()}})
                .then(function (data) {
                    return data.data.words;
                });
            return promise;
        }

    });