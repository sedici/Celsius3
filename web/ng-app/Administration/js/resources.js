administrationApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_order') + ':id',
                {id: '@id'});
    }]);

administrationApp.factory('Request', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_request') + ':order_id',
                {order_id: '@order_id'}
        );
    }]);