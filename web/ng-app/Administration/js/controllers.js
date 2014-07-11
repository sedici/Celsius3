var administrationControllers = angular.module('administrationControllers', []);

administrationControllers.controller('AdministrationCtrl', function($scope, $routeParams, $http, $filter, $translate, Order, Request) {
    'use strict';

    $scope.type = $routeParams.type;

    $scope.loadOrders = function() {
        Order.query({type: $scope.type, state: $scope.state, page: $scope.pagination.currentPage}, function(orders) {
            $scope.orders = orders;
            $scope.total = $scope.orderCount[$scope.state];

            orders.forEach(function(order) {
                order.request = Request.get({order_id: order.id});
            });
        });
    };

    $scope.pageChanged = function() {
        $scope.loadOrders();
    };

    $scope.pagination = {
        currentPage: 1
    };

    $scope.state = _.isUndefined($routeParams.state) ? 'created' : $routeParams.state;

    if (!_.isUndefined($scope.type)) {
        $http.get(Routing.generate('admin_rest_order_count_get') + '?type=' + $scope.type).success(function(response) {
            $scope.orderCount = response;
            $scope.loadOrders();
        });
    }
});