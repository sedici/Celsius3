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

orderFilters.filter('state', function () {
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
            deliver: 'ok',
            undo: 'undo',
            cancel: 'cancel',
            annul: 'annul'
        };
        return icons[input];
    };
});

orderFilters.filter('get_state', function () {
    return function (input) {
        var states = {
            search: 'searched',
            sirequest: 'requested',
            mirequest: 'requested',
            sireceive: 'received',
            mireceive: 'received',
            deliver: 'delivered',
            undo: 'undone',
            cancel: 'cancelled',
            annul: 'annulled'
        };
        return states[input];
    };
});

orderFilters.filter('first_upper', function () {
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