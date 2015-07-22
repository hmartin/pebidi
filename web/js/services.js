app
    .service('mainService', function ($rootScope, localStorageService) {
        var user = null;
        var dic = null;
        var lang = '';

        this.setDic = function (d) {
            dic = d;
            localStorageService.set('dic', dic);
        };
        this.setScore = function (dicScore) {
            user.score = dicScore;

        };
        this.setCountWord = function (countWord) {
            dic.countWord = countWord;
        };
        this.getDic = function () {
            if (!dic && localStorageService.get('dic')) {
                dic = localStorageService.get('dic');
            }
            return dic;
        };

        this.getUid = function () {
            if (user && user.id) {

                return user.id;
            } else if (localStorageService.get('user')) {
                user = localStorageService.get('user');
                return user.id;

            } else {
                return false;
            }
        };

        this.getUser = function () {
            if (!user && localStorageService.get('user')) {
                user = localStorageService.get('user');
            }
            return user;
        };
        this.setUser = function (u) {
            user = u;
            localStorageService.set('user', user);
        };
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
            $http.post(API_URL + 'tests.json', {
                uid: mainService.getUid(),
                id: did,
                nbQuestion: question,
                type: 'new'
            })
                .success(function (data) {
                    this.words = data.words;
                    this.id = data.id;
                    this.rid = data.rid;
                    $location.path('/questions');
                }.bind(this));
        }
        this.saveResults = function (points) {
            $http.post(API_URL + 'results/' + this.rid + '/saves.json', {points: points})
                .success(function (data) {
                    mainService.setUser(data.user);
                }.bind(this));
            s = 0;
            i = 0;
            this.testScore = points.reduce(function (a, b) {
                return a + b.p;
            }, 0);
            this.testScore = this.testScore * 100 / this.nbQuestion;
        }

        this.doItAgain = function () {
            $http.get(API_URL + 'tests/' + this.id + '.json')
                .success(function (data) {
                    $location.path('/questions');
                }.bind(this));
        }
        this.getTestScore = function () {
            console.log('getTestScore ');
            return this.testScore;
        }
    })


    /*
     * Add or delete pebidi's word
     */
    .service('wordService', function ($http, $rootScope, $timeout, mainService) {

    })

    /*
     * get fix dict.json
     */
    .service('dicService', function ($http, localStorageService) {
        var dic = null;
        this.loadDic = function () {
            if (!this.dic) {
                return $http.get(URL + 'dict/dict.json').then(function (res) {
                    dic = res.data;

                    return dic;
                });
            }

            return dic;
        };

        this.getDic = function () {
            return dic;
        }
    })

    /*
     * Get pedi (or gw)
     * Add word or delete word form pedi (or gw)
     */
    .service('pediService', function ($http, $rootScope, $location, $timeout, mainService) {

        this.get = function (id) {
            data = {};
            if (mainService.getUid() == id) {
                data.uid = mainService.getUid();
            }

            $http.get(API_URL + 'dictionaries/' + id + '.json', {params: data}).success(function (data) {
                $timeout(function () {
                    console.log('timeout');
                    mainService.setDic(data);
                }, 0);
            })
        };

        this.getWords = function (id) {
            //, {params: {'uid': mainService.getUid()}}
            var promise = $http
                .get(API_URL + 'dictionaries/' + id + '/words.json')
                .then(function (data) {
                    return data.data;
                });
            return promise;
        }

        this.post = function (formData) {
            mainService.setCountWord(mainService.getDic().countWord + 1);
            $http.post(API_URL + 'words.json', {
                'word': formData.word,
                'translation': formData.translation,
                'id': mainService.getDic().id
            }).success(function (data) {
                $timeout(function () {
                    mainService.setDic(data.dic);
                }, 0);
            });
        };

        this.delete = function (id) {
            $http.post(API_URL + 'words/removes.json', {
                'id': id,
                'did': mainService.getDic().id
            }).success(function (data) {
                mainService.setDic(data.dic);

            });
        }

    });