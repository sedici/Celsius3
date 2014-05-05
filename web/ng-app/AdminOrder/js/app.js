var orderApp = angular.module('orderApp', [
    'ngRoute',
    'ngResource',
    'ngAnimate',
    'ui.sortable',
    'cciWidget',
    'orderControllers',
    'orderFilters',
    'mgcrea.ngStrap',
    'angularFileUpload'
]);

orderApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: '/ng-app/AdminOrder/partials/show.html',
                    controller: 'OrderCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });
    }]);