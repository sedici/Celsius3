var userControllers = angular.module('userControllers', []);

userControllers.controller('UserCtrl', function ($scope, $http, Order, Message, User) {
    'use strict';

    $scope.assets_version = assets_version_user;

    $scope.sortData = {
        sort: 'o.createdAt',
        direction: 'desc'
    };

    $http.get(Routing.generate('user_rest_order_count_get')).success(function (response) {
        $scope.orderCount = response;
        $scope.loadOrders();
    });

    $scope.pagination = {
        currentPage: 1,
        resultsPerPage: resultsPerPage
    };

    $scope.loadOrders = function () {
        Order.query({state: 'created,searched,requested,approval_pending,received', withRequest: true, page: $scope.pagination.currentPage, sort: $scope.sortData.sort, direction: $scope.sortData.direction}, function (response) {
            $scope.orders = response.orders;
            $scope.total = $scope.orderCount.created + $scope.orderCount.searched + $scope.orderCount.requested + $scope.orderCount.approval_pending + $scope.orderCount.received;

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

    Message.query({sort: 'm.lastMessageDate', direction: 'desc', count: 3}, function (response) {
        $scope.threads = response;
    });

    $scope.user = User.get({id: user_id});

    $scope.getFileDownloadRoute = function (request, file) {
        return Routing.generate('user_file_download', {request: request.id, file: file.id});
    };

    $scope.hasDownloadableFiles = function (e) {
        return !e.is_downloaded;
    };
});
