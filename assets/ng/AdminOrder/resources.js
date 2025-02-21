var orderApp = angular.module('orderApp');
orderApp.factory('Order', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_order') + '/:id',
        {id: '@id'}
    );
}]);

orderApp.factory('Request', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_request') + ':order_id',
        {order_id: '@order_id'}
    );
}]);

orderApp.factory('Catalog', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_catalog') + ':id',
        {id: '@id'}
    );
}]);

orderApp.factory('Event', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_event') + '/:request_id/:event',
        {request_id: '@request_id', event: '@event'}
    );
}]);

orderApp.factory('Contact', ['$resource', function ($resource) {
    return $resource(Routing.generate('rest_admin_contact') + '/:institution_id',
        {institution_id: '@institution_id'}
    );
}]);

orderApp.factory('EmailTemplate', ['$resource', function ($resource) {
    return $resource(Routing.generate('rest_admin_emailtemplate_show') + ':id',
        {id: '@id'}
    );
}]);

orderApp.factory('CatalogResult', ['$resource', function ($resource) {
    return $resource(Routing.generate('admin_rest_catalog') + '/results/:order_id',
        {order_id: '@order_id'}
    );
}]);
