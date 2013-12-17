var orderApp = angular.module('orderApp', []);

orderApp.controller('OrderCtrl', function ($scope, $http) {
    $http.get(Routing.generate('admin_order_show', { id: document_id })).success(function(data) {
        $scope.order = data;
    });
});