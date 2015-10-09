/*global URL */
/*global API_URL */
/*global app */
app
    .controller('ImproveCtrl', function ($scope, $routeParams, wordService) {

        $scope.word = $routeParams.word;
        wordService.get($routeParams.word).then(function (data) {
            $scope.wordSenses = data;
        });

        $scope.addTransaltion = function() {
            var itm = document.getElementById("myList2").lastChild;
            var cln = itm.cloneNode(true);
            document.getElementById("myList1").appendChild(cln);
        }

        $scope.improveWord = function () {

        }
    })
;
