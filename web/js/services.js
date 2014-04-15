
app.service('dictionaryService', function () {
    var dictionary;

    this.set = function (dic) {
        dictionary = dic;
    };
    this.get = function () {
        return dictionary;
    };
})
.service('wordRetriever', function () {
    
    this.getWords = function (typed) {      
        $http.post(API_URL + 'get/tests.json', {'typed':typed}).success(function (data) {
                return data.words;
            });
    };
});