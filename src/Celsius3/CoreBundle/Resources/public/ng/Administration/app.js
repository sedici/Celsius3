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
                    templateUrl: 'index.html?' + assets_version_administration,
                    controller: 'AdministrationCtrl'
                }).
                when('/:type/:state', {
                    templateUrl: 'index.html?' + assets_version_administration,
                    controller: 'AdministrationCtrl'
                }).
                when('/:type/:state/:orderType', {
                    templateUrl: 'index.html?' + assets_version_administration,
                    controller: 'AdministrationCtrl'
                }).
                when('/', {
                    redirectTo: '/mine',
                }).
                otherwise({
                    redirectTo: '/'
                });

        $translateProvider.useStaticFilesLoader({
            prefix: '/bundles/celsius3core/ng/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage(_locale);
        $translateProvider.useSanitizeValueStrategy('escaped');
    }]);
