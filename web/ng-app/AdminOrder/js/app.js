var orderApp = angular.module('orderApp', [
    'ngRoute',
    'ngResource',
    'ui.sortable',
    'cciWidget',
    'orderControllers',
    'orderFilters',
    'mgcrea.ngStrap.modal'
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