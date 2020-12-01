var institutionFilters = angular.module('institutionFilters', []);

institutionFilters.filter('get_url', function () {
    return function (input) {
        return Routing.generate(input.path, input.params);
    };
});

institutionFilters.filter('first_upper', function () {
    return function (text) {
        if ((typeof text) !== 'string') {
            return text;
        }

        var words = text.split(" ");

        var t = '';
        for (var i = 0; i < words.length; i++) {
            t += words[i].charAt(0).toUpperCase() + words[i].substr(1).toLowerCase() + ' ';
        }

        return t;
    };
});