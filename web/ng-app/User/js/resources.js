userApp.factory('Order', ['$resource', function($resource) {
        return $resource(Routing.generate('user_rest_order') + ':id',
                {id: '@id'}, {
            query: {isArray: false}
        });
    }]);

userApp.factory('User', ['$resource', function($resource) {
        return $resource(Routing.generate('admin_rest_user') + ':id',
                {id: '@id'});
    }]);