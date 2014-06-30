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

orderFilters.filter('request_type_abbr', function($translate) {
    return function(type) {
        var value;
        if (type === 'search') {
            $translate('S').then(function(s) {
                value = s;
            });
        } else {
            $translate('P').then(function(p) {
                value = p;
            });
        }
        return value;
    };
});

orderFilters.filter('material_with_index', function($translate) {
    return function(input) {
        var value;
        if (input === true) {
            $translate('yes').then(function(yes) {
                value = yes;
            });
        } else {
            $translate('no').then(function(no) {
                value = no;
            });
        }
        return value;
    };
});

orderFilters.filter('get_url', function() {
    return function(input) {
        return Routing.generate(input.path, input.params);
    };
});

orderFilters.filter('state', function($translate) {
    return function(input) {
        var value;
        if (input.enabled === true) {
            $translate('enabled').then(function(enabled) {
                value = enabled;
            });
        } else {
            $translate('disabled').then(function(disabled) {
                value = disabled;
            });
        }
        return value;
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