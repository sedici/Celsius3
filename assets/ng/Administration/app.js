var administrationApp = angular.module('administrationApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'administrationControllers',
    'administrationFilters',
    'angular-loading-bar',
    'pascalprecht.translate',
    'ui.bootstrap',
    'boxuk.translation'
]);

administrationApp.config(['$routeProvider', '$translateProvider',
    function ($routeProvider, $translateProvider) {
        'use strict';
        $routeProvider
        //.when('/:type', {
        //    templateUrl: 'index.html',
        //    controller: 'AdministrationCtrl'
        //})
        .when('/:type/:state', {
            templateUrl: 'index.html',
            controller: 'AdministrationCtrl'
        }).when('/:type/:state/:orderType', {
            templateUrl: 'index.html',
            controller: 'AdministrationCtrl'
        }).when('/', {
            redirectTo: '/mine',
        }).otherwise({
            redirectTo: '/'
        });

        // $translateProvider.useStaticFilesLoader({
        //     prefix: '/build/ng/locales/locale-',
        //     suffix: '.json'
        // });
        $translateProvider.preferredLanguage(_locale);
        $translateProvider.useSanitizeValueStrategy('escaped');
    }]);
