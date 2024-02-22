var cciWidget = angular.module('cciWidget', [
    'ngResource',
    'ngSanitize',
    'ui.select',
    'pascalprecht.translate',
    'boxuk.translation'
]);

cciWidget.config(['$translateProvider',
    function($translateProvider) {
        $translateProvider.useStaticFilesLoader({
            prefix: '/bundles/celsius3core/ng/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage(_locale);
        $translateProvider.useSanitizeValueStrategy('escaped');
    }
]);
