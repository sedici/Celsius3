var orderFilters = angular.module('orderFilters', []);

orderFilters.filter('request_type', function($translate) {
    return function(type) {
        var value;
        if (type === 'search') {
            $translate('search').then(function(search) {
                value = search;
            });
        } else {
            $translate('provision').then(function(provision) {
                value = provision;
            });
        }
        return value;
    };
});

orderFilters.filter('request_type_abbr', function() {
    return function(type) {
        return type === 'search' ? 'B' : 'P';
    };
});

orderFilters.filter('material_with_index', function() {
    return function(input) {
        return input === true ? 'Yes' : 'No';
    };
});

orderFilters.filter('get_url', function() {
    return function(input) {
        return Routing.generate(input.path, input.params);
    };
});

orderFilters.filter('state', function() {
    return function(input) {
        return input.enabled === true ? 'Enabled' : 'Disabled';
    };
});

orderFilters.filter('get_history_icon', function() {
    return function(input) {
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