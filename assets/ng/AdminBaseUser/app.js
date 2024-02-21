var userApp = angular.module('userApp', [
    'pascalprecht.translate',
    'ngResource',
    'ui.bootstrap',
    'boxuk.translation',
]);

userApp.config(['$translateProvider',
    function ($translateProvider) {
        $translateProvider.useStaticFilesLoader({
            prefix: '/bundles/celsius3core/ng/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage(_locale);
        $translateProvider.useSanitizeValueStrategy('escaped');
    }]);
