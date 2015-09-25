/*global URL */
/*global API_URL */
/*global app */
app
    .controller('GroupListCtrl', function ($scope, $http, $location, $translate, Flash, mainService) {
        $http
            .get(API_URL + 'dictionary/groups/words', {params: {lang: 'en'}})
            .success(function (data) {
                $scope.groupsWords = data.groupsWords;
            });

        $scope.addGroupWord = function (id) {
            $scope.data = {};
            $scope.data.did = $scope.dic.id;
            $scope.data.gid = id;
            $http.post(API_URL + 'dictionaries/adds/groups/words', $scope.data).success(function (data) {
                mainService.setDic(data.dic);
                var message = '<strong>'+$translate('wellDone')+'!</strong> '+data.nbAdd+' '+$translate('wordsAdded')+'.';
                Flash.create('success', message, 'custom-class');
                console.log('nb add' + data.nbAdd);
            });
        };
    })
    .controller('GroupCreateCtrl', function ($scope, $http, $location, mainService) {
        $scope.formData = {};
        $scope.congrats = false;

        $scope.processForm = function () {
            $scope.congrats = true;
            $scope.formData.did = $scope.dic.id;
            $http.post(API_URL + 'dictionaries/creates/groups', $scope.formData).success(function (data) {
                mainService.setDic(data.dic);
            });
        };
    })
;
