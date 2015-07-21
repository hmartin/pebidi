app
    .service('mainService', function ($rootScope, localStorageService) {
        this.user = null;
        this.dic = null;
        this.lang = '';

        this.setDic = function (d) {
            this.dic = d;
            localStorageService.set('dic', this.dic);
            console.log(this.dic);
        };
        this.setScore = function (dicScore) {
            this.user.score = dicScore;

        };
        this.setCountWord = function (countWord) {
            this.dic.countWord = countWord;
        };
        this.getDic = function () {
            if (!this.dic && (localStorageService.get('dic'))) {
                this.dic = localStorageService.get('dic');
            }
            return this.dic;
        };

        this.getUid = function () {
            if (this.user && this.user.id) {
                
                return this.user.id;
            } else if (localStorageService.get('user')) {
                this.user = localStorageService.get('user');
                return this.user.id;
              
            } else {
                return false;
            }
        };
  
        this.getUser = function () {
            return this.user;
        };
        this.setUser = function (u) {
            this.user = u;
            localStorageService.set('user', this.user);
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

    });