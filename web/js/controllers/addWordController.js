app
    .controller('AddWordCtrl', function ($scope, $http, $location, $routeParams, $filter, Flash, pediService, mainService, wordService, dicService) {
        $scope.formData = {};

        if(!$routeParams.id && mainService.getUser()) {
            mainService.setDic(mainService.getUser().dic);
        } else if (($scope.dic && $routeParams.id && $routeParams.id != $scope.dic.id) || !$scope.dic) {
            //pediService.get($routeParams.id);
        } else {
        }

        dicService.loadDic();

        $scope.getWords = function (val) {
            var sug = $filter('limitTo')($filter('filter')(dicService.getDic(), val, function (actual, expected) {
                return actual.toString().toLowerCase().indexOf(expected.toLowerCase()) == 0;
            }), 10);
            $scope.submitCreate = false;
            if (sug.length == 0) {
                $scope.submitCreate = true;
            }
            
            sug.sort(function sortMultiDimensional(a, b) {
                return((a.w.length < b.w.length) ? -1 : ((a.w.length > b.w.length) ? 1 : 0));
            });
              
            return sug;
        };

        $scope.processWord = function () {
            pediService.post($scope.formData.word.w, mainService.getDic());
            $scope.formData.word = '';
            $scope.formData.translation = '';
        };
    })
;
