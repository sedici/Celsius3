var userFilters = angular.module('userFilters', []);

userFilters.filter('get_url', function () {
    return function (input) {
        return Routing.generate(input.path, input.params);
    };
});

userFilters.filter('request_type', function () {
    return function (type) {
        return type === 'search' ? 'busqueda' : 'provision';
    };
});

userFilters.filter('request_type_abbr', function () {
    return function (type) {
        return type === 'search' ? 's' : 'p';
    };
});