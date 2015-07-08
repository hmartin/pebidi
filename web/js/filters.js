app
    .filter('brDef', function () {
        return function (input) {
            input = input || '';
            return input.replace(/,/g, '<br/>');
        };
    })
    .filter('capitalize', function() {
        return function(input, scope) {
            return input ? input.substring(0,1).toUpperCase()+input.substring(1) : '';
        }
    });
;



app.directive('autoFocus', function($timeout) {
    return {
        restrict: 'AC',
        link: function(_scope, _element) {
            $timeout(function(){
                _element[0].focus();
            }, 0);
        }
    };
});