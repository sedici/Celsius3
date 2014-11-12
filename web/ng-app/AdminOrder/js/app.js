var orderApp = angular.module('orderApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'ngSanitize',
    'ui.sortable',
    'cciWidget',
    'stateBar',
    'orderControllers',
    'orderFilters',
    'mgcrea.ngStrap',
    'angularFileUpload',
    'angular-loading-bar',
    'pascalprecht.translate'
]);

orderApp.config(['$routeProvider', '$tooltipProvider', '$translateProvider',
    function ($routeProvider, $tooltipProvider, $translateProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: '/ng-app/AdminOrder/partials/show.html',
                    controller: 'OrderCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });
        angular.extend($tooltipProvider.defaults, {
            html: true
        });

        $translateProvider.useStaticFilesLoader({
            prefix: '/ng-app/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
    }]);