var cciWidget = angular.module('cciWidget');

cciWidget.factory('Country', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_country') + ':id',
        {id: '@id'}
    );
}]);

cciWidget.factory('City', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_city') + '/:country_id',
        {country_id: '@country_id'}
    );
}]);

cciWidget.factory('Institution', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_institution') + '/:country_id' + '/:city_id',
        {country_id: '@country_id', city_id: '@city_id'},
        {
            parent: {
                method: 'GET',
                params: {parent_id: '@parent_id'},
                url: Routing.generate('admin_rest_institution') + '/parent' + '/:parent_id',
                isArray: true
            }
        }
    );
}]);