var institutionApp = angular.module('institutionApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'ngSanitize',
    'ui.sortable',
    'institutionControllers',
    'institutionFilters',
    'mgcrea.ngStrap',
    'ngFileUpload',
    'angular-loading-bar',
    'pascalprecht.translate',
    'ngclipboard'
]);

institutionApp.config(['$routeProvider', '$tooltipProvider', '$translateProvider',
    function ($routeProvider, $tooltipProvider, $translateProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: 'institution.html?' + assets_version_admin_institution
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
