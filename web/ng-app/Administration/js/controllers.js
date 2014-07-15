var administrationControllers = angular.module('administrationControllers', []);

administrationControllers.controller('AdministrationCtrl', function($scope, $routeParams, $http, $filter, $translate, Order, User) {
    'use strict';

    $scope.type = $routeParams.type;
    $scope.sortData = {
        sort: 'createdAt',
        direction: 'desc'
    };

    $scope.loadOrders = function() {
        Order.withRequests({type: $scope.type, state: $scope.state, page: $scope.pagination.currentPage, sort: $scope.sortData.sort, direction: $scope.sortData.direction}, function(response) {
            $scope.orders = response.orders;
            $scope.total = $scope.orderCount[$scope.state];

            $scope.orders.forEach(function(order) {
                order.request = response.requests[order.id];
            });
        });
    };

    $scope.pageChanged = function() {
        $scope.loadOrders();
    };

    $scope.sort = function(field) {
        if ($scope.sortData.sort === field) {
            $scope.sortData.direction = $scope.sortData.direction === 'asc' ? 'desc' : 'asc';
        } else {
            $scope.sortData.sort = field;
            $scope.sortData.direction = 'asc';
        }

        $scope.loadOrders();
    };

    $scope.enableUser = function(user_id) {
        var data = {
            id: user_id
        };
        $http.post(Routing.generate('admin_rest_user_enable'), data).success(function(response) {
            if (response) {
                User.pending(function(users) {
                    $scope.users = users;
                });
                $('#user-modal').modal('hide');
            }
        });
    };

    $scope.showUserModal = function(user_id) {
        $scope.currentUser = _.find($scope.users, function(user) {
            return user.id === user_id;
        });
        $('#user-modal').modal('show');
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

        User.pending(function(users) {
            $scope.users = users;
        });
    }
});