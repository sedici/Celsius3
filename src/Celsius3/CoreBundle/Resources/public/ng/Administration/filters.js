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