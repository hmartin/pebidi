app
    .service('mainService', function ($rootScope, localStorageService) {
        var user = {};
        var dic = {};
        var lang = '';

        this.setDic = function (d) {
            dic = d;
            localStorageService.set('dic', dic);
            console.log(dic);
        };
        this.setScore = function (dicScore) {
            user.score = dicScore;
            console.log('scor tot' + dicScore);

        };
        this.setCountWord = function (countWord) {
            dic.countWord = countWord;
        };
        this.getDic = function () {
            if (!('id' in dic) && (localStorageService.get('dic'))) {
                dic = localStorageService.get('dic');
            }
            return dic;
        };

        this.getDid = function () {
            return dic.id;
        };

        this.getUid = function () {
            if (user.id || localStorageService.get('user')) {
                if (!user.id) {
                    user = localStorageService.get('user');
                }
                return user.id;
            } else {
                return false;
            }
        };
  
        this.getUser = function () {
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
            $http.post(API_URL + 'tests.json',  {
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
            $http.post(API_URL + 'results/'+ this.rid +'/saves.json', {points: points})
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
            $http.get(API_URL + 'tests/'+this.id+'.json')
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
     * Add
     * delete
     */
    .service('wordService', function ($http, $rootScope, $timeout, mainService) {
        this.post = function (formData) {
            mainService.setCountWord(mainService.dic.countWord + 1);
            $http.post(API_URL + 'words.json', {
                'word': formData.word,
                'translation': formData.translation,
                'id': mainService.getDid()
            }).success(function (data) {
                $timeout(function () {
                    mainService.setDic(data.dic);
                }, 0);
            });
        };

        this.delete = function (id) {
            $http.post(API_URL + 'deletes/words.json', {
                'id': id,
                'did': mainService.getDid()
            }).success(function (data) {
                mainService.setDic(data.dic);

            });
        }
    })

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
     * Create and get personal Dictionary
     */
    .service('pediService', function ($http, $rootScope, $location, $timeout, mainService) {
      /* 
       * Not Use anymore create when user create
        this.create = function (originLang, destLang) {
            $http.post(API_URL + 'creates/dics.json', {
                uid: mainService.getUid(),
                originLang: originLang,
                destLang: destLang
            }).success(function (data) {
                if (data.dic) {
                    mainService.setDic(data.dic);
                    $location.path('/addWord/' + data.dic.id);
                }
            });
        };
       */
        
        this.get = function (id) {
            data = {};
            if (mainService.getUid()) {
                data.uid = mainService.getUid();
            }
          
            $http.get(API_URL + 'dictionaries/'+ id +'.json', { params: data}).success(function (data) {
                if (data.dic) {
                    $timeout(function () {
                        console.log('timeout');
                        mainService.setDic(data.dic);
                    }, 0);
                }
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

    });