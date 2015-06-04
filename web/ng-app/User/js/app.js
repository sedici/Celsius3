var userApp = angular.module('userApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'userControllers',
    'userFilters',
    'angular-loading-bar',
    'pascalprecht.translate'
]);

userApp.config(['$routeProvider', '$translateProvider',
    function ($routeProvider, $translateProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: '/ng-app/User/partials/index.html',
                    controller: 'UserCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });

        $translateProvider.useStaticFilesLoader({
            prefix: '/ng-app/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage(_locale);
    }]);