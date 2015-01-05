var statisticsControllers = angular.module('statisticsControllers', []);
statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {

    function filterYears(years) {
        return _.filter(years, function (year) {
            return year <= $scope.currentYear;
        });
    }

    $scope.title = "Statistics";
    $scope.subtitle = "";
    $scope.searchForm = true;
    $scope.searchProvision = false;
    $scope.years;
    $scope.currentYear = new Date().getFullYear();
    $scope.requestType = 'search';
    $scope.actualMethod = function() {};
    $scope.provisionClass = '';
    $scope.searchClass = 'active';

    $scope.getUsersCountYears = function () {
        $http.get(Routing.generate('public_rest_get_users_count_years_data') + '?instance=' + instance_id)
                .success(function (response) {
                    $scope.years = filterYears(response);
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

    $scope.setTypeSearch = function () {
        $scope.searchClass = 'active';
        $scope.provisionClass = '';
        $scope.requestType = 'search';
        $scope.updateChart();
    }

    $scope.setTypeProvision = function () {
        $scope.searchClass = '';
        $scope.provisionClass = 'active';
        $scope.requestType = 'provision';
        $scope.updateChart();
    }

    //Métodos de solicitud de los datos//

    //Pasar los años final e inicial siempre y realizar el control de los años
    //en la creación de la consulta para reducir la cantidad de funciones en el
    //cliente yen el servidor reduciendo tambien e codigo repetido y simplificar
    //la generalización del codigo.

    $scope.getUsersCountDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;

        if (initialYear < finalYear) {
            $scope.getUsersCountDataForInterval(type, initialYear, finalYear);
        } else if (initialYear === finalYear) {
            $scope.getUsersCountDataForYear(type, initialYear);
        }
    };

    $scope.getUsersCountDataForInterval = function (type, initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_interval') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateUsersCountChart(response);
                });
    };

    $scope.getUsersCountDataForYear = function (type, year) {
        $http.get(Routing.generate('public_rest_get_users_count_data_for_year') + '?instance=' + instance_id + '&type=' + type + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateUsersCountChart(response);
                });
    }

    $scope.getRequestsOrigin = function (type, country, institution) {
        var parameters = '';
        if (!_.isUndefined(country)) {
            parameters += '&country=' + country;
        }
        if (!_.isUndefined(institution) && institution !== country) {
            parameters += '&institution=' + institution;
        }
        $http.get(Routing.generate('public_rest_get_requests_origin_data') + '?instance=' + instance_id + '&type=' + type + parameters)
                .success(function (response) {
                    if (!_.isUndefined(response.ids) && response.ids.length > 0 && response.categories.length > 0) {
                        $scope.ids = response.ids;
                        $scope.countries = response.countries;
                        $scope.generateRequestsOriginChart(response);
                    } else {
                        console.log('No hay Sub-Dependencias');
                    }
                });
    }

    $scope.getRequestsCountDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;

        if (initialYear < finalYear) {
            $scope.getRequestsCountDataForInterval(type, initialYear, finalYear);
        } else if (initialYear === finalYear) {
            $scope.getRequestsCountDataForYear(type, initialYear);
        }
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
        if (initialYear < finalYear) {
            $scope.getRequestsDestinyDistributionDataForInterval(type, initialYear, finalYear);
        } else if (initialYear === finalYear) {
            $scope.getRequestsDestinyDistributionDataForYear(type, initialYear);
        }
    };

    $scope.getRequestsDestinyDistributionDataForInterval = function (type, initialYear, finalYear) {
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribution_data_for_interval') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsDestinyDistributionChart(response);
                });
    }

    $scope.getRequestsDestinyDistributionDataForYear = function (type, year) {
        $http.get(Routing.generate('public_rest_get_requests_destiny_distribuion_data_for_year') + '?instance=' + instance_id + '&type=' + type + '&year=' + parseInt(year))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsDestinyDistributionChart(response);
                });
    };

    $scope.getRequestsNumberByPublicationYearDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        if (initialYear < finalYear) {
            $scope.getRequestsNumberByPublicationYearDataForInterval(type, initialYear, finalYear);
        } else if (initialYear === finalYear) {
            $scope.getRequestsNumberByPublicationYearDataForYear(type, initialYear);
        }
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
                    $scope.data = response;
                    $scope.generateRequestsNumberByPublicationYearChart(response);
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
                    data.delivered
                ],
                type: 'bar'
//                types: {
//                    'Total Pages': 'line',
//                    'Cancelled': 'bar',
//                    'Created': 'bar',
//                    'Delivered': 'bar'
//                },
//                axes: {
//                    'Total Pages': 'TP'
//                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories,
                    label: 'Years'
                },
                y: {
                    label: 'Requests count for type'
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

    $scope.updateChart = function () {
        $scope.actualMethod();
    };

    $scope.updateUsersCountChart = function () {
        $scope.getUsersCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
    };

    $scope.updateRequestsCountChart = function () {
        $scope.getRequestsCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
    };

    $scope.updateRequestsNumberByPublicationYearChart = function () {
        $scope.getRequestsNumberByPublicationYearDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
    };

    //Funciones de inicialización//

    $scope.getUsersCountData = function () {
        $scope.actualMethod = function () {
            return $scope.updateUsersCountChart();
        };
        $scope.getUsersCountYears();
        $scope.getUsersCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Users count';
        $scope.searchForm = true;
        $scope.searchProvision = false;
    };

    $scope.getRequestsOriginData = function () {
        $scope.actualMethod = function () {
            return $scope.getRequestsOrigin($scope.requestType);
        };
        $scope.getRequestsOrigin($scope.requestType);
        $scope.subtitle = 'Requests origin';
        $scope.searchForm = false;
        $scope.searchProvision = true;
    };

    $scope.getRequestsCountData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsCountChart();
        };
        $scope.getRequestsCountYears();
        $scope.getRequestsCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Requests count';
        $scope.searchForm = true;
        $scope.searchProvision = true;
    };

    $scope.getRequestsDestinyDistributionData = function () {
        $scope.actualMethod = function () {
            return $scope.getRequestsDestinyDistributionDataFor($scope.requestType, 0, $scope.currentYear);
        };
        $scope.getRequestsDestinyDistributionYears();
        $scope.getRequestsDestinyDistributionDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Requests destiny distribution';
        $scope.searchForm = true;
        $scope.searchProvision = true;
    };

    $scope.getRequestsNumberByPublicationYearData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsNumberByPublicationYearChart();
        };
        $scope.getRequestsNumberByPublicationYearYears();
        $scope.getRequestsNumberByPublicationYearDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Number of requests by publication year';
        $scope.searchForm = true;
        $scope.searchProvision = true;
    }

    $scope.start = function () {
        $scope.getUsersCountData();
    };

    $scope.start();
});