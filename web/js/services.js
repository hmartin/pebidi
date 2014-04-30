
app.service('dictionaryService', function () {
    var dictionary;

    this.set = function (dic) {
        dictionary = dic;
    };
    this.get = function () {
        return dictionary;
    };
})
.service('testService', function ($http, $location) {
    this.words = {};

    this.createTest = function (id, question) {
        $http.post(API_URL + 'creates/tests/json', {id: id, nbQuestion: question})
            .success(function (data) {
                this.words = angular.toJson(data.words);
                $location.path('/questions');
            }.bind(this));
    }
    this.saveResult = function (id, question) {
        $http.post(API_URL + 'creates/tests/json', {id: id, nbQuestion: question})
            .success(function (data) {
                this.words = angular.toJson(data.words);
                $location.path('/questions');
            }.bind(this));
    }
})

.service('wordRetriever', function () {
    
    this.getWords = function (typed) {      
        $http.post(API_URL + 'get/tests.json', {'typed':typed}).success(function (data) {
                return data.words;
            });
    };
});