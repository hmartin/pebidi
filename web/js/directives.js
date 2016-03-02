/*global app */
app
    .directive('autoFocus', function ($timeout) {
        return {
            restrict: 'AC',
            link: function (_scope, _element) {
                $timeout(function () {
                    _element[0].focus();
                }, 0);
            }
        };
    })

    .directive('senseForm', function () {
        return {
            templateUrl: 'partials/directives/senseForm.html'
        };
    })

    .directive('goHome', function () {
        return {
            template: '<a ng-href="#/dictionary"><span class="glyphicon glyphicon-arrow-left"></span> {{ "goHome"|translate }}</a>'
        };
    })
    .directive('score', function ($filter) {
      return {
        scope: {
          percent: '='
        },
        link: function(scope, element, attr) {
                if(scope.percent) {
                    element.text($filter('number')(scope.percent*100, 1) + '%'); 
                } else {
                    element.text('-'); 
                }
            }
        };
    })
;

