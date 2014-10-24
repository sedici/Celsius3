var statisticsFilters = angular.module('statisticsFilters', []);

statisticsFilters.filter('get_url', function() {
    return function(input) {
        return Routing.generate(input.path, input.params);
    };
});