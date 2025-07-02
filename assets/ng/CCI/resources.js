var cciWidget = angular.module('cciWidget');

cciWidget.factory('Country', ['$resource', function ($resource) {
    return $resource(Routing.generate('rest_admin_country_show') + ':id',
        {id: '@id'}
    );
}]);

cciWidget.factory('City', ['$resource', function ($resource) {
    return $resource(Routing.generate('rest_admin_city_show') + '/:country_id',
        {country_id: '@country_id'}
    );
}]);

cciWidget.factory('Institution', ['$resource', function ($resource) {
    return $resource(
        Routing.generate('rest_admin_institution_location') + '/:country_id' + '/:city_id',
        {country_id: '@country_id', city_id: '@city_id'},
        {
            parent: {
                method: 'GET',
                params: {parent_id: '@parent_id'},
                url: Routing.generate('rest_admin_institution_parent') + '/:parent_id',
                isArray: true
            }
        }
    );
}]);