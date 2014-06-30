var cciWidget = angular.module('cciWidget', [
    'ngResource',
    'ui.select2',
    'pascalprecht.translate'
]);

cciWidget.config(['$routeProvider', '$tooltipProvider', '$translateProvider',
    function($translateProvider) {
        $translateProvider.useStaticFilesLoader({
            prefix: '/ng-app/Directives/CCI/locales/locale-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
    }]);