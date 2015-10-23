var userControllers = angular.module('userApp');

userControllers.controller('UserCtrl', function ($scope, $translate, $http) {
    $scope.paginationActive = {
        currentPage: 1,
        resultsPerPage: resultsPerPage,
    };

    $scope.paginationReady = {
        currentPage: 1,
        resultsPerPage: resultsPerPage
    };

    $scope.paginationHistory = {
        currentPage: 1,
        resultsPerPage: resultsPerPage
    };

    $scope.countSearches = function (request) {
        return _.filter(request.events, function (e) {
            return e.type === 'search' && e.result !== 'non_searched';
        }).length;
    };

    $scope.pageChangedActive = function () {
        $http.get(Routing.generate('admin_rest_user_get_orders', {id: element_id, type: 'active'}) + '?page=' + $scope.paginationActive.currentPage)
                .success(function (response) {
                    $scope.paginationActive.total = response.total;
                    $scope.paginationActive.orders = response.orders;
                    $scope.paginationActive.numPages = Math.ceil(response.total / resultsPerPage);
                });
    };

    $scope.pageChangedReady = function () {
        $http.get(Routing.generate('admin_rest_user_get_orders', {id: element_id, type: 'ready'}) + '?page=' + $scope.paginationReady.currentPage)
                .success(function (response) {
                    $scope.paginationReady.total = response.total;
                    $scope.paginationReady.orders = response.orders;
                    $scope.paginationReady.numPages = Math.ceil(response.total / resultsPerPage);
                });
    };

    $scope.pageChangedHistory = function () {
        $http.get(Routing.generate('admin_rest_user_get_orders', {id: element_id, type: 'history'}) + '?page=' + $scope.paginationHistory.currentPage)
                .success(function (response) {
                    $scope.paginationHistory.total = response.total;
                    $scope.paginationHistory.orders = response.orders;
                    $scope.paginationHistory.numPages = Math.ceil(response.total / resultsPerPage);
                });
    };

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
                var url = Routing.generate('admin_order_show', {id: $(this).attr('data-order-id')});
                $(location).attr("href", url);
            });

});
