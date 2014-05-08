app
    .filter('brDef', function () {
        return function (input) {
            return input.replace(/,/g, '<br/>');
        };
    })
;