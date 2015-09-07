administrationApp.factory('Order', ['$resource', function ($resource) {
        return $resource(Routing.generate('admin_rest_order') + ':id',
                {id: '@id'}, {
            withRequests: {url: Routing.generate('admin_rest_order_request_get'), isArray: false}
        });
    }]);

administrationApp.factory('User', ['$resource', function ($resource) {
        return $resource(Routing.generate('admin_rest_user') + ':id',
                {id: '@id'}, {
            pending: {url: Routing.generate('admin_rest_user_pending'), isArray: true}
        });
    }]);