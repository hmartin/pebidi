/*global URL */
/*global API_URL */
/*global app */
app
    .controller('ImproveCtrl', function ($scope, $routeParams, $location) {
        console.log($routeParams.word);
      
        $scope.word = $routeParams.word;

        $scope.addTransaltion = function addTransaltion() {
            var itm = document.getElementById("myList2").lastChild;
            var cln = itm.cloneNode(true);
            document.getElementById("myList1").appendChild(cln);
        }
        
        $scope.improveWord = function () {
            
        }
    })
;
