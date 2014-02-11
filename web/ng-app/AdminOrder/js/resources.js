orderApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_order') + ':id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);

orderApp.factory('Request', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_request') + ':order_id',
                {order_id: '@order_id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);

orderApp.factory('Catalog', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_catalog') + ':id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);

orderApp.factory('CatalogSearch', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_catalogsearch') + '/:request_id',
                {request_id: '@request_id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);
