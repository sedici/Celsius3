var statisticsControllers = angular.module('statisticsControllers', []);
statisticsControllers.controller('StatisticsCtrl', function ($scope, $http) {

    function filterYears(years) {
        return _.filter(years, function (year) {
            return year <= $scope.currentYear;
        });
    }

    $scope.title = "Statistics";
    $scope.subtitle = "";
    $scope.locationFields = false;
    $scope.searchProvision = false;
    $scope.years;
    $scope.currentYear = new Date().getFullYear();
    $scope.requestType = 'search';
    $scope.initialYear = 0;
    $scope.finalYear = $scope.currentYear;
    $scope.actualMethod = function () {
    };
    $scope.provisionClass = '';
    $scope.searchClass = 'active';
    $scope.allCountries = [];
    $scope.institutions = [];
    $scope.data;
    $scope.location = {};

    $scope.countryChanged = function () {
        $http.get(Routing.generate('public_institutions', {url: instance_url}) + '?country_id=' + $scope.location.country)
                .success(function (response) {
                    $scope.institutions = response;
                });
    };

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
    };

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
    };

    $scope.setTypeProvision = function () {
        $scope.searchClass = '';
        $scope.provisionClass = 'active';
        $scope.requestType = 'provision';
        $scope.updateChart();
    };

    $scope.columnsToArray = function(data){
        var columns = $.map(data, function(value, index) {
            return [value];
        });
        
        return columns;
    }

    //Métodos de solicitud de los datos//

    $scope.getUsersCountDataFor = function (type, initialYear, finalYear) {

        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        $http.get(Routing.generate('public_rest_get_users_count_data_for') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateUsersCountChart(response);
                });
    };

    $scope.getRequestsOrigin = function (type, initialYear, finalYear, country, institution) {
        var parameters = '';
        if (!_.isUndefined(country) && country !== '') {
            parameters += '&country=' + country;
        }
        if (!_.isUndefined(institution) && institution !== country) {
            parameters += '&institution=' + institution;
        }
        if (!_.isUndefined(initialYear)) {
            parameters += '&initialYear=' + parseInt(initialYear);
        }
        if (!_.isUndefined(finalYear)) {
            parameters += '&finalYear=' + parseInt(finalYear);
        }
        $http.get(Routing.generate('public_rest_get_requests_origin_data') + '?instance=' + instance_id + '&type=' + type + parameters)
                .success(function (response) {
                    $scope.data = response;
                    $scope.ids = response.ids;
                    $scope.countries = response.countries;
                    $scope.generateRequestsOriginChart(response);
                });
    };

    $scope.getRequestsCountDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        $http.get(Routing.generate('public_rest_get_requests_count_data_for') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsCountChart(response);
                });
    };

    $scope.getRequestsDestinyDistributionDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        $http.get(Routing.generate('public_rest_get_requests_destiny_distribution_data_for') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsDestinyDistributionChart(response);
                });
    };

    $scope.getRequestsNumberByPublicationYearDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        $http.get(Routing.generate('public_rest_get_requests_number_by_publication_year_data_for') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsNumberByPublicationYearChart(response);
                });
    };
    
    $scope.getRequestsTotalDelayDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        $http.get(Routing.generate('public_rest_get_requests_total_delay_data_for') + '?instance=' + instance_id + '&type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear))
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsTotalDelayChart(response);
                });
    };
    
    //Métodos de creación del gráfico//

    $scope.generateUsersCountChart = function (data) {
        var columns = $scope.columnsToArray(data.columns);
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: columns,
                types: {
                    'Total Users': 'line',
                    'Active Users': 'bar',
                    'New Users': 'bar'
                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories
                }
            }
        });
    };

    $scope.generateRequestsOriginChart = function (data) {
        var columns = $scope.columnsToArray(data.columns);
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: columns,
                type: 'bar',
                onclick: function (d) {
                    $scope.location.country = $scope.countries[d.index];
                    $scope.countryChanged();
                    $scope.location.institution = $scope.ids[d.index];
                    $scope.getRequestsOrigin('search', $scope.initialYear, $scope.finalYear, $scope.location.country, $scope.location.institution);
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
        var columns = $scope.columnsToArray(data.columns);
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: columns,
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
        var columns = $scope.columnsToArray(data.columns);
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: columns,
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
                }
            }
        });
    };
    
    $scope.generateRequestsTotalDelayChart = function(data){
        var columns = $scope.columnsToArray(data.columns);
        var chart = c3.generate({
            data: {
                columns: columns,
                type: 'area-spline',
                groups: [['Delay 0','Delay 1','Delay 2','Delay 3','Delay 4','Delay 5','Delay 6','Delay 7','Delay 8','Delay 9']]
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories
                }
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
        $scope.locationFields = false;
        $scope.searchProvision = false;
    };

    $scope.getRequestsOriginData = function () {
        $scope.actualMethod = function () {
            return $scope.getRequestsOrigin($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.location.country, $scope.location.institution);
        };
        $scope.getRequestsOrigin($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.location.country, $scope.location.institution);
        $scope.subtitle = 'Requests origin';
        $scope.locationFields = true;
        $scope.searchProvision = true;
    };

    $scope.getRequestsCountData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsCountChart();
        };
        $scope.getRequestsCountYears();
        $scope.getRequestsCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Requests count';
        $scope.locationFields = false;
        $scope.searchProvision = true;
    };

    $scope.getRequestsDestinyDistributionData = function () {
        $scope.actualMethod = function () {
            return $scope.getRequestsDestinyDistributionDataFor($scope.requestType, 0, $scope.currentYear);
        };
        $scope.getRequestsDestinyDistributionYears();
        $scope.getRequestsDestinyDistributionDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Requests destiny distribution';
        $scope.locationFields = false;
        $scope.searchProvision = true;
    };

    $scope.getRequestsNumberByPublicationYearData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsNumberByPublicationYearChart();
        };
        $scope.getRequestsNumberByPublicationYearYears();
        $scope.getRequestsNumberByPublicationYearDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Number of requests by publication year';
        $scope.locationFields = false;
        $scope.searchProvision = true;
    };

    $scope.getRequestsTotalDelayData = function() {
        $scope.actualMethod = function () {
            return function(){};//$scope.updateRequestsNumberByPublicationYearChart();
        };
        $scope.getRequestsTotalDelayDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'Requests total delay.';
    }

    $scope.start = function () {
        $scope.getUsersCountData();
    };

    $scope.start();

    $http.get(Routing.generate('public_countries', {url: instance_url}))
            .success(function (response) {
                $scope.allCountries = response;
            });
});