//var administrationFilters = angular.module('administrationFilters', [require('angular-route')]);
var administrationFilters = angular.module('administrationFilters', []);
administrationFilters.filter('get_url', function () {
    return function (input) {
        return Routing.generate(input.path, input.params);
    };
});

administrationFilters.filter('request_type', function () {
    return function (type) {
        return type === 'search' ? 'busqueda' : 'provision';
    };
});

administrationFilters.filter('request_type_abbr', function () {
    return function (type) {
        return type === 'search' ? 's' : 'p';
    };
});

administrationFilters.filter('first_upper', function () {
    return function (text) {
        if ((typeof text) !== 'string') {
            return text;
        }

        var words = text.split(" ");

        var t = '';
        for (var i = 0; i < words.length; i++) {
            if (words[i].length > 3 || i === 0) {
                t += words[i].charAt(0).toUpperCase() + words[i].substr(1).toLowerCase() + ' ';
            } else {
                t += words[i].toLowerCase() + ' ';
            }
        }

        return t;
    };
});
