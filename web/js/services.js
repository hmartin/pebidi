
app.service('dictionaryService', function () {
    var dictionary;

    this.set = function (dic) {
        dictionary = dic;
    };
    this.get = function () {
        return dictionary;
    };
});