var statisticsApp = angular.module('statisticsApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'statisticsControllers',
    'statisticsFilters',
    'angular-loading-bar',
    'pascalprecht.translate',
    'ui.bootstrap'
]);

statisticsApp.config(['$routeProvider', '$translateProvider',
    function ($routeProvider, $translateProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: '/ng-app/Statistics/partials/index.html',
                    controller: 'StatisticsCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });

        $translateProvider.useStaticFilesLoader({
            prefix: '/ng-app/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
    }]);
