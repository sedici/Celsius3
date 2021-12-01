var statisticsFilters = angular.module('statisticsFilters', []);

statisticsFilters.filter('get_url', function () {
    return function (input) {
        return Routing.generate(input.path, input.params);
    };
});

statisticsFilters.filter('totalize_column', function () {
    return function (input) {
        return _.reduce(_.rest(input, 1), function (memo, num) {
            return memo + parseInt(num);
        }, 0);
    };
});

statisticsFilters.filter('totalize_row', function () {
    return function (input, row) {
        return _.reduce(_.zip.apply(_, _.values(input))[row], function (memo, num) {
            return memo + parseInt(num);
        }, 0);
    };
});

statisticsFilters.filter('totalize', function () {
    return function (input) {
        return _.reduce(_.rest(_.zip.apply(_, _.values(input)), 1), function (memo, num) {
            return memo + parseInt(num);
        }, 0);
    };
});
