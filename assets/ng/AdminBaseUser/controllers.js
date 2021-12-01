var userControllers = angular.module('userApp');

userControllers.controller('UserCtrl', ['$scope', '$translate', '$http',
    function ($scope, $translate, $http) {
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
            $http.get(Routing.generate('admin_rest_user_get_orders', {
                id: element_id,
                type: 'active'
            }) + '?page=' + $scope.paginationActive.currentPage)
                .then(function (response) {
                    $scope.paginationActive.total = response.data.total;
                    $scope.paginationActive.orders = response.data.orders;
                    $scope.paginationActive.numPages = Math.ceil(response.data.total / resultsPerPage);
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.pageChangedReady = function () {
            $http.get(Routing.generate('admin_rest_user_get_orders', {
                id: element_id,
                type: 'ready'
            }) + '?page=' + $scope.paginationReady.currentPage)
                .then(function (response) {
                    $scope.paginationReady.total = response.data.total;
                    $scope.paginationReady.orders = response.data.orders;
                    $scope.paginationReady.numPages = Math.ceil(response.data.total / resultsPerPage);
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.pageChangedHistory = function () {
            $http.get(Routing.generate('admin_rest_user_get_orders', {
                id: element_id,
                type: 'history'
            }) + '?page=' + $scope.paginationHistory.currentPage)
                .then(function (response) {
                    $scope.paginationHistory.total = response.data.total;
                    $scope.paginationHistory.orders = response.data.orders;
                    $scope.paginationHistory.numPages = Math.ceil(response.data.total / resultsPerPage);
                }, function (response) {
                    generateCelsiusAlert(response);
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

        $scope.printInstitutions = function (ins) {
            var txt = '';
            if (!_.isUndefined(ins)) {
                if (!_.isUndefined(ins.parent)) {
                    txt += $scope.printInstitutions(ins.parent) + ' - ' + ins.name;
                } else {
                    txt += ins.name;
                }
            }
            return txt;
        };

        $scope.hasRequests = function (events) {
            var requests = events.filter(function (e) {
                return (e.type === 'sirequest' || e.type === 'mirequest');
            });

            return (requests.length > 0);
        };
    }]);
