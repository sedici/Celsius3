var userApp = angular.module('userApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'userControllers',
    'userFilters',
    'angular-loading-bar',
    'pascalprecht.translate',
    'ui.bootstrap',
    'boxuk.translation'
]);

userApp.config(['$routeProvider', '$translateProvider',
    function ($routeProvider, $translateProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: 'index.html?' + assets_version_user
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
