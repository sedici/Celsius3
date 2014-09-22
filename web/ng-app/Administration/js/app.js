var administrationApp = angular.module('administrationApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'administrationControllers',
    'administrationFilters',
    'angular-loading-bar',
    'pascalprecht.translate',
    'ui.bootstrap'
]);

administrationApp.config(['$routeProvider', '$translateProvider',
    function ($routeProvider, $translateProvider) {
        $routeProvider.
                when('/:type', {
                    templateUrl: '/ng-app/Administration/partials/index.html',
                    controller: 'AdministrationCtrl'
                }).
                when('/:type/:state', {
                    templateUrl: '/ng-app/Administration/partials/index.html',
                    controller: 'AdministrationCtrl'
                }).
                when('/', {
                    redirectTo: '/mine',
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