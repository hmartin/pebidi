

app.controller('HomeCtrl', function ($scope, $http, $location, $cookies, dictionaryService) {
    if($cookies.dic) {
        $scope.dic = angular.fromJson($cookies.dic);
        $location.path('/addWord/' + $scope.dic.id);
    }
    $scope.processForm = function () {
        $http.post(API_URL + 'emails/onlies.json', $scope.formData).success(function (data) {
            if (data.dic) {
                $cookies.dic = angular.toJson(data.dic);
                $location.path('/addWord/' + data.dic.id);
            }
        });
    };
})


.controller('WordCtrl', function ($scope, $http, $location, $cookies, dictionaryService) {
    $scope.dic = angular.fromJson($cookies.dic);
        $scope.processForm = function () {
            $scope.formData.id = $scope.dic.id;
            $http.post(API_URL + 'news/words.json',$scope.formData ).success(function (data) {
                $scope.dic = data.dic;
                $cookies.dic = angular.toJson(data.dic);
                $scope.formData.word ='';
                $scope.formData.translation ='';
            });
        };
})

.controller('DictionnaryCtrl', function ($scope, $http, $location, $cookies, dictionaryService) {
    $scope.dic = angular.fromJson($cookies.dic);
    $http.get(API_URL + 'words.json',$scope.dic.id ).success(function (data) {
         $scope.words = data;
    });
})

.controller('rootCtrl', function ($scope, $http, $cookies, $translate) {
    $scope.changeLanguage = function (key) {
        $translate.use(key);
    };
    $scope.clearCookies = function () {
        delete $cookies['dic'];
        $location.path('/');
    };
    $scope.$watch(function() { return $cookies.dic;}, function(newValue) {        
        $scope.dic = angular.fromJson($cookies.dic);
    });
})