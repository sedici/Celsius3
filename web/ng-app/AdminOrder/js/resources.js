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

orderApp.factory('Event', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_event') + '/:request_id/:event',
                {request_id: '@request_id', event: '@event'}
        );
    }]);

orderApp.factory('Contact', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_contact') + ':institution_id',
                {institution_id: '@institution_id'}
        );
    }]);