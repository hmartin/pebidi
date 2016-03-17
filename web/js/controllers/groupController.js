/*global URL */
/*global API_URL */
/*global app */
app
    .controller('GroupListCtrl', function ($scope, $http, $location, $translate, Flash, mainService, groupService) {

        groupService.get().success(function(data) {
            $scope.groupsWords = data.groupsWords;
        });

        $scope.addGroupWord = function (id) {
            var data = {};
            data.did = $scope.user.dic.id;
            data.gid = id;
            groupService.addGroupWord($scope.data).then(function(data) {
                mainService.dic = data.dic;
                var message = '<strong>'+$translate.instant('wellDone')+'!</strong> '+data.nbAdd+' '+$translate.instant('wordsAdded')+'.';
                Flash.create('success', message, 'custom-class');
            });
        };
        
        $scope.delete = function (group) {
            $scope.groupsWords.splice($scope.groupsWords.indexOf(group), 1);
            groupService.delete(group.id);
        };
    })
    .controller('GroupCreateCtrl', function ($scope, $http, $location, mainService) {
        $scope.formData = {};
        $scope.congrats = false;

        $scope.processForm = function () {
            $scope.congrats = true;
            $scope.formData.did = $scope.dic.id;

            // Create group and return new dic
            $http.post(API_URL + 'dictionaries/creates/groups', $scope.formData).success(function (data) {
                mainService.setDic(data.dic);
            });
        };
    })
;
