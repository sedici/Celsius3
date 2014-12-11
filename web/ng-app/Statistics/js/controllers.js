var statisticsControllers = angular.module('statisticsControllers', []);
statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {
    $scope.title = "Statistics";
    $scope.subtitle = "";
    $scope.section = "";
    $scope.searchForm = true;
    $scope.years;
    
    //Métodos para los formularios de busqueda//
    
    $scope.getUsersCountYears = function () {
        $http.get(Routing.generate('public_rest_get_users_count_years_data') + '?instance=' + instance_id)
                .success(function (response) {
            $scope.years = response;
        });
    };
    
    $scope.getRequestsCountYears = function () {
        $http.get(Routing.generate('public_rest_get_requests_count_years_data') + '?instance=' + instance_id)
                .success(function (response) {
            $scope.years = response;
        });
    };
    
    $scope.getRequestsDestinyDistributionYears = function(){
        // IMPL
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
        
    //Métodos de solicitud de los datos//

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
                }).error(function (response) {
            var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
            ventana.document.write(response);
        });
    };
    
    $scope.getUsersCountDataForYear = function (year) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_year') + '?instance=' + instance_id + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateUsersCountChart(response);
                }).error(function (response) {
            var ventana = window.open("", "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=800");
            ventana.document.write(response);
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
                });
    }
    
    $scope.getRequestsCountDataFor = function(initialYear,finalYear) {
        if (initialYear < finalYear)
            $scope.getRequestsCountDataForInterval(initialYear, finalYear);
        if (initialYear === finalYear)
            $scope.getRequestsCountDataForYear(initialYear);
    }
    
    $scope.getRequestsCountDataForInterval = function (initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_requests_count_data_for_interval') + '?instance=' + instance_id + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
            .success(function (response) {
                $scope.data = response;
                $scope.generateRequestsCountChart(response);
            });
    };
    
    $scope.getRequestsCountDataForYear = function (year) {
        $http.get(Routing.generate('public_rest_get_requests_count_data_for_year') + '?instance=' + instance_id + '&year=' + parseInt(year))
            .success(function (response) {
                $scope.generateRequestsCountChart(response);
                $scope.data = response;
            });
    };
    
    $scope.getRequestsDestinyDistributionDataFor = function(type,initialYear,finalYear){
        if (initialYear < finalYear)
            $scope.getRequestsDestinyDistributionDataForInterval(type,initialYear, finalYear);
        if (initialYear === finalYear)
            $scope.getRequestsDestinyDistributionDataForYear(type,initialYear);
    };
    
    $scope.getRequestsDestinyDistributionDataForInterval = function(type,initialYear, finalYear){
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribution_data_for_interval') + '?instance=' + instance_id + '&type='+ type +'&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
            .success(function (response) {
                $scope.data = response;
                $scope.generateRequestsDestinyDistributionChart(response);
            });
    }
    
    $scope.getRequestsDestinyDistributionDataForYear = function (type,year) {
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribuion_data_for_year') + '?instance=' + instance_id + '&type=' + type +'&year=' + parseInt(year))
            .success(function (response) {
                $scope.generateRequestsDestinyDistributionChart(response);
                $scope.data = response;
            });
    };
    
    //Métodos de creación del gráfico//

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
    
    $scope.generateRequestsOriginChart = function(data) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    data.requestsCount
                ],
                type: 'bar',
                onclick: function (d) {
                    $scope.getRequestsOrigin('search', $scope.countries[d.index], $scope.ids[d.index]);
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
    
    $scope.generateRequestsCountChart = function(data) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    data.cancelled,
                    data.created,
                    data.delivered,
                    data.totalPages
                ],
                type: 'bar',
                types: {
                    'Total Pages': line
                }
//                groups: [
//                    ['Cancelled', 'Delivered','Created']
//                ]
            },
            axis: {
                x: {
                    type: 'category',
                    categories: $.unique(data.categories)
                }
            },
            grid: {
                y: {
                    lines: [{value:0}]
                }
            }
        });
    }
    
    $scope.generateRequestsDestinyDistributionChart = function(data) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    data.cancelled,
                    data.created,
                    data.delivered
                ],
                type: 'bar',
//                groups: [
//                    ['Cancelled', 'Delivered','Created']
//                ]
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories
                }
            },
            grid: {
                y: {
                    lines: [{value:0}]
                }
            }
        });
    }
    
    //Métodos de actualización del grafico//
    
    $scope.updateUsersCountChart = function () {
        $scope.getUsersCountDataFor($scope.initialYear, $scope.finalYear);
    };
    
    $scope.updateRequestsCountChart = function () {
        $scope.getRequestsCountDataFor($scope.initialYear, $scope.finalYear);
    };
    
    //Métodos de inicialización//
    
    $scope.getUsersCountData = function(){
        $scope.getUsersCountYears();
        $scope.getUsersCountDataFor(0, 3000);
        $scope.section = '/ng-app/Statistics/partials/usersCount.html';
        $scope.subtitle = 'Users count';
    };
    
    $scope.getRequestsOriginData = function () {
        $scope.getRequestsOrigin('search');
        $scope.section = '/ng-app/Statistics/partials/requestsOrigin.html';
        $scope.subtitle = 'Requests origin';
    };
    
    $scope.getRequestsDestinyDistributionData = function(){
        $scope.getRequestsDestinyDistributionYears();
        $scope.getRequestsDestinyDistributionDataFor('search',0,3000);
        $scope.subtitle = 'Requests destiny distribution';
        $scope.searchForm = true;
    };
    
    $scope.start = function (){
//        $scope.getUsersCountData();
        $scope.getRequestsDestinyDistributionData();
    };
    
    $scope.start();
});