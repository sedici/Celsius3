var userControllers = angular.module('userApp');

userControllers.controller('UserCtrl', function ($scope, $translate, $http) {
    $scope.paginationActive = {
        currentPage: 1,
        total: 0,
        resultsPerPage: resultsPerPage,
        orders: []
    };

    $scope.paginationReady = {
        currentPage: 1,
        resultsPerPage: resultsPerPage
    };

    $scope.paginationHistory = {
        currentPage: 1,
        resultsPerPage: resultsPerPage
    };

    $scope.pageChangedActive = function () {
        $http.get(Routing.generate('admin_rest_user_get_orders', {id: element_id, type: 'active'}) + '?currentPage=' + $scope.paginationActive.currentPage + '&resultsPerPage=' + $scope.paginationActive.resultsPerPage)
                .success(function (response) {
                    $scope.paginationActive.total = response.total;
                    $scope.paginationActive.orders = response.orders;
                });
    }

    $scope.pageChangedReady = function () {
        $http.get(Routing.generate('admin_rest_user_get_orders', {id: element_id, type: 'ready'}) + '?currentPage=' + $scope.paginationReady.currentPage + '&resultsPerPage=' + $scope.paginationReady.resultsPerPage)
                .success(function (response) {
                    $scope.paginationReady.total = response.total;
                    $scope.paginationReady.orders = response.orders;
                });
    }

    $scope.pageChangedHistory = function () {
        $http.get(Routing.generate('admin_rest_user_get_orders', {id: element_id, type: 'history'}) + '?currentPage=' + $scope.paginationHistory.currentPage + '&resultsPerPage=' + $scope.paginationHistory.resultsPerPage)
                .success(function (response) {
                    $scope.paginationHistory.total = response.total;
                    $scope.paginationHistory.orders = response.orders;
                });
    }

    $scope.pageChangedActive();
    $scope.pageChangedReady();
    $scope.pageChangedHistory();

    $(document)
            .on('mouseover', 'div.main_info', function () {
                $(this).addClass('order-on-mouseover');
                $(this).css('cursor', 'pointer');
            })
            .on('mouseout', 'div.main_info', function () {
                $(this).removeClass('order-on-mouseover');
                $(this).css('cursor', 'auto');
            })
            .on('click', 'div.main_info', function () {
                var url = Routing.generate('admin_order_show', {id: $(this).attr('data-order-id')})
                $(location).attr("href", url);
            });

});
