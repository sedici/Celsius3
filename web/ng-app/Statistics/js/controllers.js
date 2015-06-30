var statisticsControllers = angular.module('statisticsControllers', []);

statisticsControllers.controller('StatisticsCtrl', function ($scope, $http, $routeParams) {

    function filterYears(years) {
        return _.filter(years, function (year) {
            return year <= $scope.currentYear;
        });
    };
    $scope.statistic = (_.isUndefined($routeParams.statistic)) ? 'request_count' : $routeParams.statistic;
    $scope.title = "Statistics";
    $scope.subtitle = "";
    $scope.description = "";
    $scope.locationFields = false;
    $scope.searchProvision = false;
    $scope.selectDelayType = false;
    $scope.delayType = 'totalDelay';
    $scope.years;
    $scope.currentYear = new Date().getFullYear();
    $scope.requestType = 'search';
    $scope.initialYear = 2001;
    $scope.finalYear = $scope.currentYear;
    $scope.actualMethod = function () {
    };
    $scope.provisionClass = '';
    $scope.searchClass = 'active';
    $scope.allCountries = [];
    $scope.institutions = [];
    $scope.data;
    $scope.firstColumnTitle = '';
    $scope.location = {};

    if (directory) {
        instance_id = null;
        instance_url = 'directory';
    }
    $scope.countryChanged = function () {
        $http.get(Routing.generate('public_institutions', {url: instance_url}) + '?country_id=' + $scope.location.country)
                .success(function (response) {
                    $scope.institutions = response;
                });
    };

    $scope.generateYears = function () {
        var years = new Array();
        for (i = 2001; i <= $scope.currentYear; i++) {
            years.push(i);
        }
        $scope.years = years;
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
    };

    $scope.setTypeProvision = function () {
        $scope.searchClass = '';
        $scope.provisionClass = 'active';
        $scope.requestType = 'provision';
        $scope.updateChart();
    };

    $scope.columnsToArray = function (data) {
        var columns = $.map(data, function (value, index) {
            return [value];
        });

        return columns;
    };

    $scope.columnsForOneYear = function (data, year) {
        var columns = new Array();

        columns.push(year);
        data = $scope.columnsToArray(data);

        for (var i = 0; i < data.length; i++) {
            columns.push(data[i][1]);
        }

        return columns;
    };

    //Métodos de solicitud de los datos//

    $scope.getUsersCountDataFor = function (type, initialYear, finalYear) {

        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        var params = '?';
        if (!_.isNull(instance_id)) {
            params += 'instance=' + instance_id + '&';
        }
        params += 'type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear);

        $http.get(Routing.generate('public_rest_get_users_count_data_for') + params)
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

        var params = '?';
        if (!_.isNull(instance_id)) {
            params += 'instance=' + instance_id + '&';
        }
        ;
        params += 'type=' + type + parameters;

        $http.get(Routing.generate('public_rest_get_requests_origin_data') + params)
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

        var params = '?';
        if (!_.isNull(instance_id)) {
            params += 'instance=' + instance_id + '&';
        }
        ;
        params += 'type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear);

        $http.get(Routing.generate('public_rest_get_requests_count_data_for') + params)
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsCountChart(response);
                });
    };

    $scope.getRequestsDestinyDistributionDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        var params = '?';
        if (!_.isNull(instance_id)) {
            params += 'instance=' + instance_id + '&';
        }
        ;
        params += 'type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear);

        $http.get(Routing.generate('public_rest_get_requests_destiny_distribution_data_for') + params)
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsDestinyDistributionChart(response);
                });
    };

    $scope.getRequestsNumberByPublicationYearDataFor = function (type, initialYear, finalYear) {
        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;

        var params = '?';
        if (!_.isNull(instance_id)) {
            params += 'instance=' + instance_id + '&';
        }
        ;
        params += 'type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear);

        $http.get(Routing.generate('public_rest_get_requests_number_by_publication_year_data_for') + params)
                .success(function (response) {
                    $scope.data = response;
                    $scope.generateRequestsNumberByPublicationYearChart(response);
                });
    };

    $scope.getRequestsTotalDelayDataFor = function (type, initialYear, finalYear, delayType) {

        initialYear = _.isUndefined(initialYear) ? 0 : initialYear;
        finalYear = _.isUndefined(finalYear) ? $scope.currentYear : finalYear;
        type = _.isUndefined(type) ? 'search' : type;
        delayType = _.isUndefined(delayType) ? 'totalDelay' : delayType;

        var params = '?';
        if (!_.isNull(instance_id)) {
            params += 'instance=' + instance_id + '&';
        }
        ;
        params += 'type=' + type + '&initialYear=' + parseInt(initialYear) + '&finalYear=' + parseInt(finalYear) + '&delayType=' + delayType;

        $http.get(Routing.generate('public_rest_get_requests_total_delay_data_for') + params)
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
        var columns = {};
        columns.requestsCount = data.columns.requestsCount.slice(0, 11);
        var columns = $scope.columnsToArray(columns);
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
                    categories: data.categories.slice(0, 11),
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
        columns.push(data.totalPages);
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: columns,
                types: {
                    'Total Pages': 'line',
                    'Cancelled': 'bar',
                    'Created': 'bar',
                    'Satisfied': 'bar'
                },
                axes: {
                    'Cancelled': 'y',
                    'Created': 'y',
                    'Satisfied': 'y',
                    'Total Pages': 'y2'
                }
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories,
                    label: 'Years'
                },
                y: {
                    label: 'Requests count for type',
                },
                y2: {
                    show: true
                }
            },
            grid: {
                y: {
                    lines: [{value: 0}]
                }
            }
        });
    };

    $scope.generateRequestsDestinyDistributionChart = function (data) {
        console.log(data);
        var columns = {};
        columns.created = data.columns.created.slice(0, 11);
        columns.cancelled = data.columns.cancelled.slice(0, 11);
        columns.delivered = data.columns.delivered.slice(0, 11);
        columns = $scope.columnsToArray(columns);
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: columns,
                type: 'bar'
            },
            axis: {
                x: {
                    type: 'category',
                    categories: data.categories.slice(0, 10),
                    label: 'Countries'
                },
                y: {
                    label: 'Requests count for country'
                }
            },
            grid: {
                y: {
                    lines: [{value: 0}]
                }
            }
        });
    }

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

    $scope.generateRequestsTotalDelayChart = function (data) {
        console.log(data);
        var groups = ['Delay 0', 'Delay 1', 'Delay 2', 'Delay 3', 'Delay 4', 'Delay 5', 'Delay 6', 'Delay 7', 'Delay 8', 'Delay 9'];
        var columns = (data.categories.length > 1) ? $scope.columnsToArray(data.columns) : [$scope.columnsForOneYear(data.columns, data.categories[0])];
        var chartType = (data.categories.length > 1) ? 'area-spline' : 'bar';
        var chartGroups = (data.categories.length > 1) ? groups : [];
        var chartCategories = (data.categories.length > 1) ? data.categories : groups;
        console.log(chartCategories);
        var chart = c3.generate({
            data: {
                columns: columns,
                type: chartType,
                groups: [chartGroups]
            },
            axis: {
                x: {
                    type: 'category',
                    categories: chartCategories
                }
            }
        });
    };

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

    $scope.updateRequestsTotalDelay = function () {
        $scope.getRequestsTotalDelayDataFor($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.delayType);
    };

    //Funciones de inicialización//

    $scope.getUsersCountData = function () {
        $scope.actualMethod = function () {
            return $scope.updateUsersCountChart();
        };
        $scope.getUsersCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'usersCount';
        $scope.locationFields = false;
        $scope.searchProvision = false;
        $scope.selectDelayType = false;
        $scope.firstColumnTitle = 'Year';
        $scope.description = 'usersCountDescription';
    };

    $scope.getRequestsOriginData = function () {
        $scope.actualMethod = function () {
            return $scope.getRequestsOrigin($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.location.country, $scope.location.institution);
        };
        $scope.getRequestsOrigin($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.location.country, $scope.location.institution);
        $scope.subtitle = 'requestsOrigin';
        $scope.locationFields = true;
        $scope.searchProvision = true;
        $scope.selectDelayType = false;
        $scope.firstColumnTitle = 'Country';
        $scope.description = 'requestsOriginDescription';
    };

    $scope.getRequestsCountData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsCountChart();
        };
        $scope.getRequestsCountDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'requestsCount';
        $scope.locationFields = false;
        $scope.searchProvision = true;
        $scope.selectDelayType = false;
        $scope.firstColumnTitle = 'Year';
        $scope.description = 'requestsCountDescription';
    };

    $scope.getRequestsDestinyDistributionData = function () {
        $scope.actualMethod = function () {
            return $scope.getRequestsDestinyDistributionDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        };
        $scope.getRequestsDestinyDistributionDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'requestsDestinyDistribution';
        $scope.locationFields = false;
        $scope.searchProvision = true;
        $scope.selectDelayType = false;
        $scope.firstColumnTitle = 'Country';
        $scope.description = 'requestsDestinyDistributionDescription';
    };

    $scope.getRequestsNumberByPublicationYearData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsNumberByPublicationYearChart();
        };
//        $scope.getRequestsNumberByPublicationYearYears();
        $scope.getRequestsNumberByPublicationYearDataFor($scope.requestType, $scope.initialYear, $scope.finalYear);
        $scope.subtitle = 'requestsPublicationYear';
        $scope.locationFields = false;
        $scope.searchProvision = true;
        $scope.selectDelayType = false;
        $scope.firstColumnTitle = 'Publication year';
        $scope.description = 'requestsPublicationYearDescription';
    };

    $scope.getRequestsTotalDelayData = function () {
        $scope.actualMethod = function () {
            return $scope.updateRequestsTotalDelay();
        };
        $scope.getRequestsTotalDelayDataFor($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.delayType);
        $scope.subtitle = 'requestsTotalDelay.';
        $scope.selectDelayType = true;
        $scope.locationFields = false;
        $scope.searchProvision = true;
        $scope.firstColumnTitle = 'Year';
        $scope.description = 'serviceEfficiencyDescription';
    };

    $scope.getAverageDelayByDestinyCountryData = function () {
        $scope.actualMethod = function () {
            return $scope.updateAverageDelayByDestinyCountry();
        };
        $scope.getAverageDelayByDestinyCountryFor($scope.requestType, $scope.initialYear, $scope.finalYear, $scope.delayType);
        $scope.subtitle = 'Average delay by destiny country.';
        $scope.selectDelayType = false;
        $scope.locationFields = false;
        $scope.searchProvision = true;
        $scope.firstColumnTitle = 'Year';
        $scope.description = ''
    };

    $scope.start = function () {
        $scope.generateYears();
        $scope.getRequestsCountData();
    };

    $scope.start();
    $http.get(Routing.generate('public_countries', {url: instance_url}))
            .success(function (response) {
                $scope.allCountries = response;
            });
});
