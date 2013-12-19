var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function ($scope, $http) {
    $http.get(Routing.generate('admin_rest_order_show', { id: document_id })).success(function(data) {
        $scope.order = data.order;
        $scope.request = data.request;
    });
});