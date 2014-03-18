orderApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_order') + ':id',
                {id: '@id'}
        );
    }]);

orderApp.factory('Request', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_request') + ':order_id',
                {order_id: '@order_id'}
        );
    }]);

orderApp.factory('Catalog', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_catalog') + ':id',
                {id: '@id'}
        );
    }]);

orderApp.factory('CatalogSearch', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_catalogsearch') + '/:request_id',
                {request_id: '@request_id'}
        );
    }]);

orderApp.factory('Country', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_country') + ':id',
                {id: '@id'}
        );
    }]);

orderApp.factory('City', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_city') + '/:country_id',
                {country_id: '@country_id'}
        );
    }]);

orderApp.factory('Institution', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_institution') + '/:country_id' + '/:city_id',
                {country_id: '@country_id', city_id: '@city_id'},
        {parent: {method: 'GET', params: {parent_id: '@parent_id'}, url: Routing.generate('admin_rest_institution') + '/parent' + '/:parent_id', isArray: true}}
        );
    }]);