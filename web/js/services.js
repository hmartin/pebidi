/*global API_URL */
/*global URL */
/*global app */
app
    .service('mainService', function ($rootScope, localStorageService) {
        var user = null;
        var dic = null;

        this.setDic = function (d) {
            dic = d;
            localStorageService.set('dic', dic);
        };

        this.setUserDic = function (d) {
            user.dic = d;
        };

        this.setScore = function (dicScore) {
            user.score = dicScore;

        };
        this.setCountWord = function (countWord) {
            user.dic.countWord = countWord;
        };

        this.getDic = function () {
            if (!dic && localStorageService.get('dic')) {
                dic = localStorageService.get('dic');
            }
            return dic;
        };

        this.getUser = function () {
            if (!user && localStorageService.get('user')) {
                user = localStorageService.get('user');
            }
            return user;
        };
        this.setUser = function (u) {
            user = u;
            console.log(u);
            localStorageService.set('user', user);
        };

        this.isMainDic = function () {
            if (user.dic.id == dic.id) {
                return true;
            }

            return false;
        }
    })

    .service('testService', function ($http, $location, mainService) {
        this.words = {};
        this.nbQuestion = 0;
        this.id = 0;
        this.did = 0;
        this.rid = 0;
        this.testScore = 0;

        this.createTest = function (did, question) {
            this.nbQuestion = question;
            this.did = did;
            $http.post(API_URL + 'tests', {
                uid: mainService.getUser().id,
                id: did,
                nbQuestion: question,
                type: 'new'
            }).success(function (data) {
                this.words = data.words;
                this.id = data.id;
                this.rid = data.rid;
                $location.path('/questions');
            }.bind(this));
        };

        this.saveResults = function (points) {
            $http.post(API_URL + 'results/' + this.rid + '/saves', {points: points})
                .success(function (data) {
                    mainService.getUser().score = data.score;
                });

            this.testScore = points.reduce(function (a, b) {
                return a + b.p;
            }, 0);
            this.testScore = this.testScore * 100 / this.nbQuestion;
        };

        this.doItAgain = function () {
            $http.get(API_URL + 'tests/' + this.id)
                .success(function (data) {
                    $location.path('/questions');
                });
        };

        this.getTestScore = function () {
            return this.testScore;
        };
    })


    /*
     * Add or delete pebidi's word
     */
    .service('wordService', function ($http, $rootScope, $timeout, $q) {
        var word = null;

        this.get = function (idOrWord) {
            var deferred = $q.defer();

            if (word && (word[0].id == idOrWord || word[0].w == idOrWord)) {
                deferred.resolve(word);
            } else {
                $http.get(API_URL + 'words/' + idOrWord).then(function (data) {
                    word = data.data;
                    deferred.resolve(word);
                });
            }

            return deferred.promise;
        };


        this.improve = function (word, data) {
            $http.post(API_URL + 'words/improves', {'word': word, 'data': data}).success(function (data) {
                console.log(data);
            });
        };
    })

    /*
     * get fix dict.json
     */
    .service('dicService', function ($http, localStorageService) {
        var dic = null;
        this.loadDic = function () {
            if (!dic) {
                return $http.get(URL + 'dict/dicten.json').then(function (res) {
                    dic = res.data;


                    return dic;
                });
            }

            return dic;
        };

        this.getDic = function () {
            return dic;
        };
    })

    /*
     * Get pedi (or gw)
     * Add word or delete word form pedi (or gw)
     */
    .service('pediService', function ($http, $rootScope, $location, $timeout, $translate, Flash, mainService) {
        this.get = function (id) {
            var data = {};
            data.uid = mainService.getUser().id;

            $http.get(API_URL + 'dictionaries/' + id, {params: data}).success(function (data) {
                $timeout(function () {
                    mainService.setDic(data);
                }, 0);
            });
        };

        this.getWords = function (id) {
            var promise = $http
                .get(API_URL + 'dictionaries/' + id + '/words')
                .then(function (data) {
                    return data.data;
                });
            return promise;
        };

        this.post = function (word, dic) {

            if (mainService.isMainDic()) {
                mainService.setCountWord(dic.countWord + 1);
            }

            $http.post(API_URL + 'words', {
                'w': word,
                'id': dic.id
            }).success(function (data) {
                if (data.msg == 'notExistYet') {
                    Flash.create('warning', $translate.instant('notExistYet'));
                }

                console.log(data);
                console.log(data.dic);
                if (mainService.isMainDic()) {
                    mainService.setDic(data.dic);
                }
                if (mainService.getUser().dic.id == data.dic.id) {

                    mainService.getUser().dic = data.dic;
                    console.log(mainService.getUser().dic);
                }
            });
        };

        this.delete = function (id) {
            $http.post(API_URL + 'words/removes', {
                'id': id,
                'did': mainService.getDic().id
            }).success(function (data) {
                mainService.getUser().dic = data.dic;

            });
        };

    })

    /*
     * Manage group (create and delete)
     */
    .service('groupService', function ($http, $rootScope, $location, $timeout, mainService) {
        this.get = function () {
            return $http.get(API_URL + 'dictionary/groups/words', {params: {lang: 'en'}});
        };

        this.addGroupWord = function (data) {
            console.log(data);
            return $http.post(API_URL + 'dictionaries/adds/groups/words', data);
        };

        this.delete = function (id) {
            var promise = $http.post(API_URL + 'groups/removes', {
                'id': id,
                'did': mainService.getUser().dic.id
            }).then(function (data) {
                return data.groupsWords;
            });

            return promise;
        };
    });
    