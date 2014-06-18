app
    .filter('brDef', function () {
        return function (input) {
            return input.replace(/,/g, '<br/>');
        };
    })
    .filter('capitalize', function() {
        return function(input, scope) {
            return input ? input.substring(0,1).toUpperCase()+input.substring(1) : '';
        }
    });
;