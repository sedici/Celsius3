var statisticsControllers = angular.module('statisticsControllers', []);
statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {
    $scope.title = "Statistics";
    $scope.subtitle = "";
    $scope.searchForm = true;
    $scope.years;
    $scope.requestType = 'search';
    $scope.actualMethod = '';

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

    $scope.getRequestsDestinyDistributionYears = function () {
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribution_years_data') + '?instance=' + instance_id)
                .success(function (response) {
                    $scope.years = response;
                });
    };

    $scope.getRequestsNumberByPublicationYearYears = function () {
        $http.get(Routing.generate('public_rest_get_requests_number_by_publication_year_years_data') + '?instance=' + instance_id)
                .success(function (response) {
                    $scope.years = response;
                });
    }

    $scope.initialYearChange = function () {
        if ($scope.finalYear < $scope.initialYear) {
            $scope.initialYear = $scope.finalYear;
        }
    };

    $scope.finalYearChange = function () {
        if ($scope.finalYear < $scope.initialYear) {
            $scope.finalYear = $scope.initialYear;
        }
    };

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
                });
    };

    $scope.getUsersCountDataForYear = function (year) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_year') + '?instance=' + instance_id + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.generateUsersCountChart(response);
                    $scope.data = response;
                });
    }

    $scope.getRequestsOrigin = function (type, country, institution) {
        var parameters = '';
        if (country !== undefined)
            parameters += '&country=' + country;
        if (institution !== undefined && institution !== country)
            parameters += '&institution=' + institution;
        $http.get(Routing.generate('public_rest_get_requests_origin_data') + '?instance=' + instance_id + '&type=' + type + parameters)
                .success(function (response) {
                    if (response.ids !== undefined && response.ids.length > 0 && response.categories.length > 0) {
                        $scope.ids = response.ids;
                        $scope.countries = response.countries;
                        $scope.generateRequestsOriginChart(response);
                    } else {
                        alert('No hay Sub-Dependencias');
                    }
                });
    }

    $scope.getRequestsCountDataFor = function (type, initialYear, finalYear) {
        if (initialYear < finalYear)
            $scope.getRequestsCountDataForInterval(type, initialYear, finalYear);
        if (initialYear === finalYear)
            $scope.getRequestsCountDataForYear(type, initialYear);
    }

    $scope.getRequestsCountDataForInterval = function (type, initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_requests_count_data_for_interval') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsCountChart(response);
                });
    };

    $scope.getRequestsCountDataForYear = function (type, year) {
        $http.get(Routing.generate('public_rest_get_requests_count_data_for_year') + '?instance=' + instance_id + '&type=' + type + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.generateRequestsCountChart(response);
                    $scope.data = response;
                });
    };

    $scope.getRequestsDestinyDistributionDataFor = function (type, initialYear, finalYear) {
        if (initialYear < finalYear)
            $scope.getRequestsDestinyDistributionDataForInterval(type, initialYear, finalYear);
        if (initialYear === finalYear)
            $scope.getRequestsDestinyDistributionDataForYear(type, initialYear);
    };

    $scope.getRequestsDestinyDistributionDataForInterval = function (type, initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribution_data_for_interval') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    console.log(response);
                    $scope.data = response;
                    $scope.generateRequestsDestinyDistributionChart(response);
                });
    }

    $scope.getRequestsDestinyDistributionDataForYear = function (type, year) {
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribuion_data_for_year') + '?instance=' + instance_id + '&type=' + type + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.generateRequestsDestinyDistributionChart(response);
                    $scope.data = response;
                });
    };

    $scope.getRequestsNumberByPublicationYearDataFor = function (type, initialYear, finalYear) {
        initialYear = (initialYear === undefined)? 0 : initialYear;
        finalYear = (finalYear === undefined)? 3000 : finalYear;
        if (initialYear < finalYear)
            $scope.getRequestsNumberByPublicationYearDataForInterval(type, initialYear, finalYear);
        if (initialYear == finalYear)
            $scope.getRequestsNumberByPublicationYearDataForYear(type, initialYear);
    };

    $scope.getRequestsNumberByPublicationYearDataForInterval = function (type, initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_requests_number_by_publication_year_data_for_interval') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsNumberByPublicationYearChart(response);
                });
    }

    $scope.getRequestsNumberByPublicationYearDataForYear = function (type, year) {
        $http.get(Routing.generate('public_rest_get_requests_number_by_publication_year_data_for_year') + '?instance=' + instance_id + '&type=' + type + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.generateRequestsNumberByPublicationYearChart(response);
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

    $scope.generateRequestsOriginChart = function (data) {
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

    $scope.generateRequestsCountChart = function (data) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    data.cancelled,
                    data.created,
                    data.delivered,
                    data.totalPages
                ],
                types: {
                    'Total Pages': 'line',
                    'Cancelled': 'bar',
                    'Created': 'bar',
                    'Delivered': 'bar'
                },
//                axes: {
//                    'Total Pages': 'TP'
//                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: $.unique(data.categories),
                    label: 'Años'
                },
                y: {
                    label: 'Requests count for type'
                },
                y2: {
                    show: true,
                    label: 'Total Pages'
                }
            },
            grid: {
                y: {
                    lines: [{value: 0}]
                }
            }
        });
    };

    $scope.generateRequestsNumberByPublicationYearChart = function (data) {
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    data.counts
                ],
                type: 'area'
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories,
                    label: 'Años de publicacion',
                    tick: {
                        values: data.tickValue
                    }
                },
            }
        });
    }

    //Métodos de actualización de gráfico//
    
    $scope.updateChart = function() {
        eval($scope.actualMethod);
    };
    
    $scope.updateUsersCountChart = function () {
        $scope.getUsersCountDataFor($scope.initialYear, $scope.finalYear);
    };

    $scope.updateRequestsCountChart = function () {
        $scope.getRequestsCountDataFor($scope.initialYear, $scope.finalYear);
    };
    
    $scope.updateRequestsNumberByPublicationYearChart = function () {
        $scope.getRequestsNumberByPublicationYearDataFor($scope.requestType,$scope.initialYear, $scope.finalYear);
    };

    //Funciones de inicialización//

    $scope.getUsersCountData = function () {
        $scope.actualMethod = '$scope.updateUsersCountChart()';
        $scope.getUsersCountYears();
        $scope.getUsersCountDataFor(0, 3000);
        $scope.subtitle = 'Users count';
        $scope.searchForm = true;
    };

    $scope.getRequestsOriginData = function () {
        $scope.getRequestsOrigin($scope.requestType);
        $scope.subtitle = 'Requests origin';
        $scope.searchForm = false;
    };

    $scope.getRequestsCountData = function () {
        $scope.actualMethod = '$scope.updateRequestsCountChart()';
        $scope.getRequestsCountYears();
        $scope.getRequestsCountDataFor('search', 0, 3000);
        $scope.subtitle = 'Requests count';
        $scope.searchForm = true;
    };

    $scope.getRequestsDestinyDistributionData = function () {
        $scope.getRequestsDestinyDistributionYears();
        $scope.getRequestsDestinyDistributionDataFor($scope.requestType,0,3000);
        $scope.subtitle = 'Requests destiny distribution';
        $scope.searchForm = true;
    };

    $scope.getRequestsNumberByPublicationYearData = function () {
        $scope.actualMethod = '$scope.updateRequestsNumberByPublicationYearChart()';
        $scope.getRequestsNumberByPublicationYearYears();
        $scope.getRequestsNumberByPublicationYearDataFor($scope.requestType,0,3000);
        $scope.subtitle = 'Number of requests by publication year';
        $scope.searchForm = true;
    }

    $scope.start = function () {
        $scope.getUsersCountData();
    };

    $scope.start();
});