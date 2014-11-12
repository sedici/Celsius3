var statisticsControllers = angular.module('statisticsControllers', []);

statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {
    $scope.title = "Statistics";

    $scope.getYears = function () {
        $http.get(Routing.generate('public_rest_get_years_data')).success(function (response) {
            $scope.years = response;
        });
    };

    $scope.showChart = function () {
        $scope.getUsersCountDataFor($scope.initialYear, $scope.finalYear);
    };

    $scope.getUsersCountDataFor = function (initialYear, finalYear) {
        if (initialYear < finalYear)
            $scope.getUsersCountDataForInterval(initialYear, finalYear);
        if (initialYear === finalYear)
            $scope.getUsersCountDataForYear(initialYear);
    };

    $scope.getUsersCountDataForInterval = function (initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_interval') + '?initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateChart(response);
                }).error(function(response){
                    var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
                    ventana.document.write(response);
                });
    };

    $scope.getUsersCountDataForYear = function (year) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_year') + '?year=' + parseInt(year))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateChart(response);
                }).error(function(response){
                    var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
                    ventana.document.write(response);
                });
    };

    $scope.generateChart = function (response) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    response.totalUsers,
                    response.activeUsers,
                    response.newUsers
                ],
                types: {
                    'Total Users': 'line',
                    'Active Users': 'bar',
                    'New Users': 'bar'
                },
                onclick: function (d, i) { $scope. }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: response.categories
                }
            }
        });
    };
      
    $scope.initialYearChange = function () {
        if ($scope.finalYear < $scope.initialYear) {
            $scope.initialYear = $scope.finalYear;
        }
    }

    $scope.finalYearChange = function () {
        if ($scope.finalYear < $scope.initialYear) {
            $scope.finalYear = $scope.initialYear;
        }
    }

    $scope.start = function () {
        $scope.getYears();
        $scope.getUsersCountDataFor(0, 3000);
    };

    $scope.start();
});