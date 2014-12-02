var statisticsControllers = angular.module('statisticsControllers', []);

statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {
    $scope.title = "Statistics";
    $scope.subtitle = "";
    $scope.section = "";
    
    $scope.getYears = function () {
        $http.get(Routing.generate('public_rest_get_years_data') + '?instance=' + instance_id).success(function (response) {
            $scope.years = response;
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
    
    $scope.updateUsersCountChart = function () {
        $scope.getUsersCountDataFor($scope.initialYear, $scope.finalYear);
    };

    $scope.getUsersCountDataFor = function (initialYear, finalYear) {
        if (initialYear < finalYear)
            $scope.getUsersCountDataForInterval(initialYear, finalYear);
        if (initialYear === finalYear)
            $scope.getUsersCountDataForYear(initialYear);
    };

    $scope.getUsersCountDataForInterval = function (initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_interval') + '?instance=' + instance_id + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateUsersCountChart(response);
                }).error(function(response){
                    var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
                    ventana.document.write(response);
                });
    };

    $scope.getUsersCountDataForYear = function (year) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_year') + '?instance=' + instance_id + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateUsersCountChart(response);
                }).error(function(response){
                    var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
                    ventana.document.write(response);
                });
    };

    $scope.generateUsersCountChart = function (response) {
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
                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: response.categories
                }
            }
        });
    };
    
    $scope.getRequestsOrigin = function(type,country,institution) {
        var parameters = '';
        if(country !== undefined) parameters += '&country=' + country;
        if(institution !== undefined && institution !== country) parameters += '&institution=' + institution;
        $http.get(Routing.generate('public_rest_get_requests_origin_data') + '?instance=' + instance_id + '&type=' + type + parameters)
                .success(function (response) {
                    if(response.ids !== undefined && response.ids.length > 0 && response.categories.length > 0){
                        $scope.ids = response.ids;
                        $scope.countries = response.countries;
                        $scope.generateRequestsOriginChart(response);
                    } else {
                        alert('No hay Sub-Dependencias');
                    }
                }).error(function(response){
                    var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
                    ventana.document.write(response);
                });
    }
    
    $scope.generateRequestsOriginChart = function(data) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    data.requestsCount
                ],
                type: 'bar',
                onclick: function(d) {
                    $scope.getRequestsOrigin('search',$scope.countries[d.index],$scope.ids[d.index]);
                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories,
                    tick: {
                        rotate: 20
                    },
                    height: 50
                }
            }
        });
    };
    
    //Funciones de inicialización//
    
    $scope.getUsersCountData = function(){
        $scope.getYears();
        $scope.getUsersCountDataFor(0, 3000);
        $scope.section = '/ng-app/Statistics/partials/usersCount.html';
        $scope.subtitle = 'Users count';
    };
    
    $scope.getRequestsOriginData = function(){
        $scope.getRequestsOrigin('search');
        $scope.section = '/ng-app/Statistics/partials/requestsOrigin.html';
        $scope.subtitle = 'Requests origin';
    };
    
    $scope.start = function (){
        $scope.getUsersCountData();
    };

    $scope.start();
});