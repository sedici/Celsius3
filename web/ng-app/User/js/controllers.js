var userControllers = angular.module('userControllers', []);

userControllers.controller('UserCtrl', function($scope, $routeParams, $http, $filter, $translate, Order, User) {
    'use strict';

    Order.query({state: 'created,searched,requested,approval_pending,delivered', withRequest: true, sort: 'updated_at', direction: 'desc'}, function(response) {
        $scope.orders = response.orders;

        $scope.orders.forEach(function(order) {
            order.request = response.requests[order.id];
        });
    });
});