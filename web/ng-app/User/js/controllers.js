var userControllers = angular.module('userControllers', []);

userControllers.controller('UserCtrl', function ($scope, Order, Message, User) {
    'use strict';

    Order.query({state: 'created,searched,requested,approval_pending,received', withRequest: true, sort: 'updated_at', direction: 'desc'}, function (response) {
        $scope.orders = response.orders;

        $scope.orders.forEach(function (order) {
            order.request = response.requests[order.id];
        });
    });

    Message.query({sort: 'lastMessageDate', direction: 'desc', count: 3}, function (response) {
        $scope.threads = response;
    });

    $scope.user = User.get({id: user_id});

    $scope.getFileDownloadRoute = function (request, file) {
        return Routing.generate('user_file_download', {request: request.id, file: file.id});
    };
});