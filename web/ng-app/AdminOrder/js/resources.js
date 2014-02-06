orderApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_order_show') + '/:id',
                {id: '@id'},
        {charge: {method: 'POST', params: {charge: true}, isArray: false}});
    }]);

