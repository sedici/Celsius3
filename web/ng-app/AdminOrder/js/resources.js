orderApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_order_get') + '/:id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);

orderApp.factory('Request', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_request_get') + '/:order_id',
                {order_id: '@order_id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);
