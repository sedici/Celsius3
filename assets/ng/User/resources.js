var userApp = angular.module('userApp');

userApp.factory('Order', ['$resource', function ($resource) {
    return $resource(Routing.generate('user_rest_order') + ':id',
        {id: '@id'}, {
            query: {isArray: false}
        });
}]);

userApp.factory('User', ['$resource', function ($resource) {
    return $resource(Routing.generate('user_rest_user') + ':id',
        {id: '@id'});
}]);

userApp.factory('Message', ['$resource', function ($resource) {
    return $resource(Routing.generate('rest_message') + ':id',
        {id: '@id'});
}]);