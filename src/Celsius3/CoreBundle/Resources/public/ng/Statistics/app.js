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
                    templateUrl: 'index.html'
                }).
                otherwise({
                    redirectTo: '/'
                });

        $translateProvider.useStaticFilesLoader({
            prefix: '/bundles/celsius3core/ng/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage(_locale);
    }]);
