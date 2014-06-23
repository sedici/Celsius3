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
    'angularFileUpload',
    'angular-loading-bar'
]);

orderApp.config(['$routeProvider', '$tooltipProvider',
    function($routeProvider, $tooltipProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: '/ng-app/AdminOrder/partials/show.html',
                    controller: 'OrderCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });
        angular.extend($tooltipProvider.defaults, {
            html: true
        });
    }]);