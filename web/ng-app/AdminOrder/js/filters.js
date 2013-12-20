var orderFilters = angular.module('orderFilters', []);

orderFilters.filter('request_type', function() {
    return function(type) {
        return type === 'search' ? 'busqueda' : 'provision';
    };
});

orderFilters.filter('request_type_abbr', function() {
    return function(type) {
        return type === 'search' ? 'B' : 'P';
    };
});

orderFilters.filter('request_operator', function() {
    return function(operator) {
        return operator ? operator : '-';
    };
});

orderFilters.filter('material_with_index', function() {
    return function(input) {
        return input === true ? 'Yes' : 'No';
    };
});