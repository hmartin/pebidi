/*global URL */
/*global API_URL */
/*global app */
app
    .controller('GroupListCtrl', function ($scope, $http, $location, $uibModal, $translate, Flash, mainService, groupService) {
        groupService.get().success(function (data) {
            $scope.groupsWords = data.groupsWords;
        });

        $scope.addGroupWord = function (group) {

            if (group.countWord > 3) {
                $scope.tempGroupId = group.id;
                var modalInstance = $uibModal.open({
                    templateUrl: 'myModalContent.html',
                    controller: 'GroupListCtrl',
                    scope: $scope
                });
            } else {
                addGroup(group.id, 20);
            }
        };

        $scope.addConfirm = function (limit) {
            addGroup($scope.tempGroupId, limit);
        }

        var addGroup = function (id, limit) {

            groupService.addGroupWord({'did': $scope.user.dic.id, 'gid': id, 'limit': limit}).then(function (data) {
                mainService.setDic(data.dic);
                var message = '<strong>' + $translate.instant('wellDone') + '!</strong> ' + data.nbAdd + ' ' + $translate.instant('wordsAdded') + '.';
                Flash.create('success', message, 'custom-class');
            });
        };

        $scope.delete = function (group) {
            $scope.groupsWords.splice($scope.groupsWords.indexOf(group), 1);
            $http.delete(API_URL + 'dictionaries/' + group.id);
        };

        $scope.createTest = function (group) {
            mainService.setDic(group);
            $location.path('/createTest/' + group.id + '/' + group.slug);
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
                mainService.getUser().dic = data.dic;
            });
        };
    })
;
