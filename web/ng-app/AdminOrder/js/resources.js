orderApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_order') + ':id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}}});
    }]);

orderApp.factory('Request', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_request') + ':order_id',
                {order_id: '@order_id'},
        {charge: {method: 'POST', params: {charge: true}}});
    }]);

orderApp.factory('Catalog', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_catalog') + ':id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}}});
    }]);

orderApp.factory('CatalogSearch', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_catalogsearch') + '/:request_id',
                {request_id: '@request_id'},
        {charge: {method: 'POST', params: {charge: true}}});
    }]);

orderApp.factory('Country', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_country') + ':id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}}});
    }]);

orderApp.factory('City', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_city') + '/:country_id',
                {country_id: '@country_id'},
        {charge: {method: 'POST', params: {charge: true}}});
    }]);
