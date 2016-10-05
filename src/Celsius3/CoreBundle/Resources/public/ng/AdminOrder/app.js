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
    'ngFileUpload',
    'angular-loading-bar',
    'pascalprecht.translate',
    'ngclipboard',
    'summernote',
    'boxuk.translation'
]);

orderApp.config(['$routeProvider', '$tooltipProvider', '$translateProvider',
    function ($routeProvider, $tooltipProvider, $translateProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: 'show.html?' + assets_version_admin_order
                }).
                otherwise({
                    redirectTo: '/'
                });
        angular.extend($tooltipProvider.defaults, {
            html: true
        });

        $translateProvider.useStaticFilesLoader({
            prefix: '/bundles/celsius3core/ng/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage(_locale);
        $translateProvider.useSanitizeValueStrategy('escaped');
    }]);
