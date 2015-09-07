var administrationControllers = angular.module('administrationControllers', []);

administrationControllers.controller('AdministrationCtrl', function ($scope, $routeParams, $http, Order, User) {
    'use strict';

    $scope.type = $routeParams.type;
    $scope.sortData = {
        sort: 'o.createdAt',
        direction: 'desc'
    };

    $scope.searchPending = function (request) {
        return request.states.filter(function (item) {
            return item.type === 'requested' && item.search_pending;
        }).length > 0;
    };

    $scope.loadOrders = function () {
        var state;
        if ($scope.state === 'finished') {
            state = ['delivered', 'cancelled', 'annulled'];
        } else {
            state = $scope.state;
        }

        Order.withRequests({type: $scope.type, "state[]": state, page: $scope.pagination.currentPage, sort: $scope.sortData.sort, direction: $scope.sortData.direction, orderType: $scope.orderType}, function (response) {
            $scope.orders = response.orders;
            if ($scope.state === 'finished') {
                $scope.total = $scope.orderCount.delivered + $scope.orderCount.cancelled + $scope.orderCount.annulled;
            } else {
                $scope.total = $scope.orderCount[state];
            }

            $scope.numPages = Math.ceil($scope.total / 10);

            $scope.orders.forEach(function (order) {
                order.request = response.requests[order.id];
            });
        });
    };

    $scope.pageChanged = function () {
        $scope.loadOrders();
    };

    $scope.sort = function (field) {
        if ($scope.sortData.sort === field) {
            $scope.sortData.direction = $scope.sortData.direction === 'asc' ? 'desc' : 'asc';
        } else {
            $scope.sortData.sort = field;
            $scope.sortData.direction = 'asc';
        }

        $scope.loadOrders();
    };

    $scope.enableUser = function (user_id) {
        var data = {
            id: user_id
        };
        $http.post(Routing.generate('admin_rest_user_enable'), data).success(function (response) {
            if (response) {
                User.pending(function (users) {
                    $scope.users = users;
                });
                $('#user-modal').modal('hide');
            }
        });
    };

    $scope.showUserModal = function (user_id) {
        $scope.currentUser = _.find($scope.users, function (user) {
            return user.id === user_id;
        });
        $('#user-modal').modal('show');
    };

    $scope.isActive = function (state) {
        return state === $scope.state ? 'active' : '';
    };

    $scope.isActiveType = function (type) {
        return type === $scope.type ? 'active' : '';
    };

    $scope.isActiveOrderType = function (orderType) {
        return orderType === $scope.orderType ? 'active' : '';
    };

    $scope.pagination = {
        currentPage: 1
    };

    $scope.state = _.isUndefined($routeParams.state) ? 'created' : $routeParams.state;

    $scope.orderType = _.isUndefined($routeParams.orderType) ? 'allTypes' : $routeParams.orderType;

    if (!_.isUndefined($scope.type)) {
        $http.get(Routing.generate('admin_rest_order_count_get') + '?type=' + $scope.type + '&state=' + $scope.state + '&orderType=' + $scope.orderType).success(function (response) {
            $scope.orderCount = response;
            $scope.loadOrders();
        });

        User.pending(function (users) {
            $scope.users = users;
        });
    }
});