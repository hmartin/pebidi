/*global URL */
/*global API_URL */
/*global app */

app
    .controller('AddWordCtrl', function ($scope, $http, $location, $routeParams, $filter, Flash, pediService, mainService, wordService, dicService) {
        $scope.formData = {};

        if (($scope.dic && $routeParams.id && $routeParams.id != $scope.dic.id) || !$scope.dic) {
            //pediService.get($routeParams.id);
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
            var string;
            console.log($scope.formData);
            if (typeof $scope.formData.word === 'object') {
                string = $scope.formData.word.w;
            } else {
                string = $scope.formData.word;
            }
            pediService.post(string, mainService.getDic());
            $scope.formData.word = '';
            $scope.formData.translation = '';
        };
        
        
        $scope.doATest = function () {
            $location.path('/createTest/');
        };
    })
;
