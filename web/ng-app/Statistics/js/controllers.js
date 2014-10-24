var statisticsControllers = angular.module('statisticsControllers', []);

statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {
    $scope.title = "Statistics";

    $scope.getUsersCountData = function () {
        $http.get(Routing.generate('public_rest_get_users_count_data')).success(function (response) {
            $scope.data = response;
            var chart = c3.generate({
                data: {
                    columns: [
                        response.total_users,
                        response.active_users
                    ],
                    type: 'bar'
                },
                axis: {
                    x: {
                        type: 'category',
                        categories: response.categories
                    }
                }
            });
        }).error(function () {
            $scope.data = "ERROR";
        });
    };

    $scope.getUsersCountData();
});
