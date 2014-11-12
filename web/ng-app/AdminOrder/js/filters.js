var orderFilters = angular.module('orderFilters', []);

orderFilters.filter('request_type', function () {
    return function (type) {
        return type === 'search' ? 'busqueda' : 'provision';
    };
});

orderFilters.filter('request_type_abbr', function () {
    return function (type) {
        return type === 'search' ? 's' : 'p';
    };
});

orderFilters.filter('material_with_index', function () {
    return function (input) {
        return input === true ? 'yes' : 'no';
    };
});

orderFilters.filter('get_url', function () {
    return function (input) {
        return Routing.generate(input.path, input.params);
    };
});

orderFilters.filter('state', function ($translate) {
    return function (input) {
        return input.enabled === true ? 'enabled' : 'disabled';
    };
});

orderFilters.filter('get_history_icon', function () {
    return function (input) {
        var icons = {
            search: 'lupa1',
            sirequest: 'reloj1',
            mirequest: 'reloj2',
            sireceive: 'papel1',
            mireceive: 'papel2',
            deliver: 'ok'
        };
        return icons[input];
    };
});
