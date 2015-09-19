/*global URL */
/*global API_URL */
/*global app */
app
    .controller('GroupListCtrl', function ($scope, $http, $location, mainService) {
        $http
            .get(API_URL + 'dictionary/groups/words', {params: {lang: 'en'}})
            .success(function (data) {
                $scope.groupsWords = data.groupsWords;
            });

        $scope.addGroupWord = function (id) {
            $scope.data = {};
            $scope.data.did = $scope.dic.id;
            $scope.data.gwid = id;
            $http.post(API_URL + 'adds/groups/words', $scope.data).success(function (data) {
                mainService.setDic(data.dic);
            });
        };
    })
    .controller('GroupCreateCtrl', function ($scope, $http, $location, mainService) {
        $scope.formData = {};

        $scope.processForm = function () {
            $scope.formData.did = $scope.dic.id;
            $http.post(API_URL + 'dictionaries/creates/groups', $scope.formData).success(function (data) {
                mainService.setDic(data.dic);
            });
        };
    })
;
